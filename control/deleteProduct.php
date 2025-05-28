<?php
include "../model/db.php";

if (isset($_GET['productID'])) {
    $productID = $_GET['productID'];

    $db = new mydb();
    $conn = $db->openCon();

    $deleteQuery = "DELETE FROM products WHERE productID = '$productID'";

    if ($conn->query($deleteQuery) === TRUE) {
        echo "Product deleted successfully!";
        header("Location: seller2.php");
        exit;
    } else {
        echo "Error deleting product: " . $conn->error;
    }

    $db->closeCon($conn);
} else {
    echo "Product ID is missing!";
}
?>
