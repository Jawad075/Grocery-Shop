<?php
session_start();

include '../model/db.php';

$db = new mydb();
$conn = $db->openCon();

if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $productID => $quantity) {
        if ($quantity < 1) {
            echo "<script>alert('Quantity must be at least 1 for product ID: $productID');</script>";
            continue;
        }

        $query = "SELECT prUnits FROM products WHERE productID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $productID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $availableStock = $row['prUnits'];

            if ($quantity <= $availableStock) {
                $_SESSION['cart'][$productID] = $quantity;
            } else {
                echo "<script>alert('Insufficient stock for product ID: $productID. Available stock: $availableStock');</script>";
            }
        } else {
            echo "<script>alert('Product ID: $productID not found in the database.');</script>";
        }

        $stmt->close();
    }
}

header('Location: customerCart.php');
exit;
?>
