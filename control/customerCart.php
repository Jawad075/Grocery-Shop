<?php
session_start();

include '../model/db.php';

$db = new mydb();
$conn = $db->openCon();

$cartItems = [];
$totalAmount = 0;
$checkoutMessage = "";

if (isset($_GET['remove'])) {
    $productID = $_GET['remove'];
    if (isset($_SESSION['cart'][$productID])) {
        unset($_SESSION['cart'][$productID]);
        header('Location: customerCart.php');
        exit;
    }
}

if (isset($_POST['update_cart']) && isset($_POST['quantity'])) {
    foreach ($_POST['quantity'] as $productID => $quantity) {
        if ($quantity > 0) {
            $_SESSION['cart'][$productID] = $quantity;
        } else {
            unset($_SESSION['cart'][$productID]);
        }
    }
    header('Location: customerCart.php');
    exit;
}

if (isset($_POST['checkout'])) {
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $customerID = $_SESSION['userID'];

        foreach ($_SESSION['cart'] as $productID => $quantity) {
            $updateStock = $conn->prepare("UPDATE products SET prUnits = prUnits - ? WHERE productID = ? AND prUnits >= ?");
            $updateStock->bind_param("iii", $quantity, $productID, $quantity);
            $updateStock->execute();

            $insert = $conn->prepare("INSERT INTO inventory (customerID, productID, quantity) VALUES (?, ?, ?)");
            $insert->bind_param("iii", $customerID, $productID, $quantity);
            $insert->execute();
        }

        unset($_SESSION['cart']);
        $checkoutMessage = " Order placed successfully and added to inventory!";
    } else {
        $checkoutMessage = " Cart is empty. Nothing to checkout.";
    }
}

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $productID => $quantity) {
        $query = "SELECT * FROM products WHERE productID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $productID);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if ($product) {
            $product['quantity'] = $quantity;
            $product['totalPrice'] = $product['prPrice'] * $quantity;
            $cartItems[] = $product;
            $totalAmount += $product['totalPrice'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - Grocery Store</title>
    <link rel="stylesheet" href="../view/cart.css">
</head>
<body>

<h2>Your Cart</h2>

<?php if (!empty($checkoutMessage)) : ?>
    <p style="color: green; font-weight: bold;"><?php echo $checkoutMessage; ?></p>
<?php endif; ?>

<form action="customerCart.php" method="POST">
    <table border="1">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($cartItems)) : ?>
            <?php foreach ($cartItems as $product) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['prName']); ?></td>
                    <td>$<?php echo number_format($product['prPrice'], 2); ?></td>
                    <td>
                        <input type="number" name="quantity[<?php echo $product['productID']; ?>]" value="<?php echo $product['quantity']; ?>" min="1">
                    </td>
                    <td>$<?php echo number_format($product['totalPrice'], 2); ?></td>
                    <td><a href="customerCart.php?remove=<?php echo $product['productID']; ?>">Remove</a></td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr><td colspan="5">Your cart is empty.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <?php if (!empty($cartItems)) : ?>
        <p><strong>Total Amount: $<?php echo number_format($totalAmount, 2); ?></strong></p>
        <button type="submit" name="checkout">Proceed to Checkout</button>
    <?php endif; ?>
</form>

<p>
    <a href="customer2.php"> Continue Shopping</a> |
    <a href="inventory.php">View My Inventory</a>
</p>

</body>
</html>
