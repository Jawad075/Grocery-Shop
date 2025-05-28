<?php
session_start();

if (!isset($_SESSION['userID']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include "../model/db.php";

$db = new mydb();
$conn = $db->openCon();

$query = "SELECT productID, prName, prPrice, prUnits, prCategory FROM products WHERE prUnits > 0";
$result = $conn->query($query);
$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['quantity'])) {
    foreach ($_POST['quantity'] as $productID => $quantity) {
        if ($quantity > 0) {
            $_SESSION['cart'][$productID] = $quantity;
        }
    }

    header("Location: customerCart.php");
    exit();
}

$db->closeCon($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grocery Store - Customer Page</title>
    <link rel="stylesheet" href="../view/cus2.css">
</head>
<body>

<div class="order-history-wrapper">
    <button class="order-history-button" title="Inventory" onclick="window.location.href='inventory.php'">🧾</button>
    <div class="order-history-label">Inventory</div>
</div>

<div class="profile-wrapper">
    <button class="profile-button" title="View Profile" onclick="window.location.href='customerProfile.php'">👤</button>
    <div class="profile-label">Profile</div>
</div>

<div id="image-container">
    <img src="../control/cus.png" id="grocery-image">
</div>

<header id="header">
    <h1>Buy your Grocery Items</h1>
</header>

<div class="container">
    <h2>Available Products</h2>
    <form action="customer2.php" method="post">
        <table border="1">
            <tr>
                <th>Serial</th>
                <th>Product Details</th>
                <th>Available Units</th>
                <th>Quantity & Action</th>
            </tr>

            <?php foreach ($products as $product): ?>
            <tr>
                <td><?php echo $product['productID']; ?></td>
                <td>
                    <strong><?php echo $product['prName']; ?></strong><br>
                    Category: <?php echo isset($product['prCategory']) ? $product['prCategory'] : 'Not Available'; ?><br>
                    Price: $<?php echo $product['prPrice']; ?>
                </td>
                <td id="available_<?php echo $product['productID']; ?>"><?php echo $product['prUnits']; ?> units</td>
                <td>
                    <button type="button" onclick="changeQty('<?php echo $product['productID']; ?>', -1)">-</button>
                    <input type="number" name="quantity[<?php echo $product['productID']; ?>]" id="product_<?php echo $product['productID']; ?>_qty" value="0" min="0" data-price="<?php echo $product['prPrice']; ?>">
                    <button type="button" onclick="changeQty('<?php echo $product['productID']; ?>', 1)">+</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <div class="order-summary">
            <h3>Total Amount: $<span id="totalAmount">0.00</span></h3>
        </div>

        <button class="place_order" type="submit">Place Order</button>
    </form>
</div>

<script src="../view/cus2.js"></script>

</body>
</html>
