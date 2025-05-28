<?php
include "../model/db.php";

if (isset($_GET['productID'])) {
    $productID = $_GET['productID'];

    $db = new mydb();
    $conn = $db->openCon();

    $query = "SELECT * FROM products WHERE productID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $prName = $row['prName'];
        $prCategory = $row['prCategory'];
        $prPrice = $row['prPrice'];
        $prUnits = $row['prUnits'];
    } else {
        echo "Product not found!";
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $product_name = $_POST["product_name"];
        $category = $_POST["category"];
        $price = $_POST["price"];
        $available_units = $_POST["available_units"];

        $updateQuery = "UPDATE products SET prName = ?, prCategory = ?, prPrice = ?, prUnits = ? WHERE productID = ?";
        if ($stmtUpdate = $conn->prepare($updateQuery)) {
            $stmtUpdate->bind_param("ssdis", $product_name, $category, $price, $available_units, $productID);
            $stmtUpdate->execute();

            echo "<p style='color:green;text-align:center;'>Product updated successfully!</p>";
        } else {
            echo "Error: " . $conn->error;
        }

        $stmtUpdate->close();
    }

    $db->closeCon($conn);
} else {
    echo "Product ID is missing!";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Product</title>
    <link rel="stylesheet" href="../view/updateProduct.css">
</head>
<body>
<div class="container" style="width: 400px; margin: 40px auto;">

    <h2 style="text-align:center;">Update Product</h2>

    <form method="POST">
        <label>Product Name:</label>
        <input type="text" name="product_name" value="<?php echo htmlspecialchars($prName ?? ''); ?>" required><br>

        <label>Category:</label>
        <input type="text" name="category" value="<?php echo htmlspecialchars($prCategory ?? ''); ?>" required><br>

        <label>Price:</label>
        <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($prPrice ?? ''); ?>" required><br>

        <label>Available Units:</label>
        <input type="number" name="available_units" value="<?php echo htmlspecialchars($prUnits ?? ''); ?>" required><br>

        <input type="submit" value="Update Product">
    </form>

    <a href="seller2.php"><button type="button" style="margin-top:15px; width:100%;">Back to Product List</button></a>

</div>
</body>
</html>
