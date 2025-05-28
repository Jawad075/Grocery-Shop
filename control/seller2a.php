<?php
include "../model/db.php";

if (!isset($_SESSION['userID']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$product_name = $category = $price = $available_units = "";
$product_name_err = $category_err = $price_err = $units_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["product_name"])) {
        $product_name_err = "Product Name is required";
    } else {
        $product_name = trim($_POST["product_name"]);
        if (!preg_match("/^[a-zA-Z0-9\s]+$/", $product_name)) {
            $product_name_err = "Only letters, numbers, and spaces allowed";
        }
    }

    if (empty($_POST["category"])) {
        $category_err = "Category is required";
    } else {
        $category = $_POST["category"];
    }

    if (empty($_POST["price"])) {
        $price_err = "Price is required";
    } elseif (!is_numeric($_POST["price"]) || $_POST["price"] <= 0) {
        $price_err = "Enter a valid price";
    } else {
        $price = $_POST["price"];
    }

    if (empty($_POST["available_units"])) {
        $units_err = "Available Units is required";
    } elseif (!is_numeric($_POST["available_units"]) || $_POST["available_units"] <= 0) {
        $units_err = "Enter valid unit count";
    } else {
        $available_units = $_POST["available_units"];
    }

    if (empty($product_name_err) && empty($category_err) && empty($price_err) && empty($units_err)) {
        $db = new mydb();
        $conn = $db->openCon();

        $sql = "INSERT INTO products (prName, prCategory, prPrice, prUnits) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssdi", $product_name, $category, $price, $available_units);
            if ($stmt->execute()) {
                echo " Product added successfully!";
            } else {
                echo " Error: " . $stmt->error;
            }
            $stmt->close();
        }

        $db->closeCon($conn);
    }
}

function getAllProducts($conn) {
    return $conn->query("SELECT * FROM products");
}
?>
