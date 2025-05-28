<?php
session_start();

if (
    !isset($_SESSION['userID']) ||
    !isset($_SESSION['username']) ||
    !isset($_SESSION['userType']) ||
    $_SESSION['userType'] !== 'seller'
) {
    header("Location: login.php");
    exit();
}

include "../control/seller2a.php";

$db = new mydb();
$conn = $db->openCon();

$products = getAllProducts($conn);

$db->closeCon($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Grocery Store - Manage Products</title>
    <link rel="stylesheet" href="../view/sell2.css">
</head>
<body>

<div class="profile-wrapper">
    <button class="profile-button" title="View Profile" onclick="window.location.href='sellerProfile.php'">👤</button>
    <div class="profile-label">Profile</div>
</div>

<div id="image-container">
    <img src="../control/cart.png" id="grocery-image" alt="Grocery">
</div>

<header id="header">
    <h1>Welcome to the Grocery Store</h1>
</header>

<div class="container">
    <h2>Manage Products</h2>

    <form id="addProductForm" action="seller2.php" method="POST">
        <table border="1">
            <tr>
                <td>Product Name:</td>
                <td>
                    <input type="text" name="product_name" id="product_name" value="<?php echo htmlspecialchars($product_name); ?>">
                    <div class="error"><?php echo $product_name_err; ?></div>
                </td>
            </tr>
            <tr>
                <td>Category:</td>
                <td>
                    <select name="category" id="category">
                        <option value="">Select</option>
                        <option value="grains" <?= $category == 'grains' ? 'selected' : '' ?>>Grains</option>
                        <option value="vegetables" <?= $category == 'vegetables' ? 'selected' : '' ?>>Vegetables</option>
                        <option value="dairy" <?= $category == 'dairy' ? 'selected' : '' ?>>Dairy</option>
                        <option value="bakery" <?= $category == 'bakery' ? 'selected' : '' ?>>Bakery</option>
                        <option value="eggs" <?= $category == 'eggs' ? 'selected' : '' ?>>Eggs</option>
                        <option value="fruits" <?= $category == 'fruits' ? 'selected' : '' ?>>Fruits</option>
                        <option value="pantry" <?= $category == 'pantry' ? 'selected' : '' ?>>Pantry Essentials</option>
                        <option value="household" <?= $category == 'household' ? 'selected' : '' ?>>Household Items</option>
                        <option value="meat" <?= $category == 'meat' ? 'selected' : '' ?>>Meat & Poultry</option>
                    </select>
                    <div class="error"><?php echo $category_err; ?></div>
                </td>
            </tr>
            <tr>
                <td>Price (per unit):</td>
                <td>
                    <input type="number" step="1.00" name="price" id="price" value="<?php echo htmlspecialchars($price); ?>">
                    <div class="error"><?php echo $price_err; ?></div>
                </td>
            </tr>
            <tr>
                <td>Available Units:</td>
                <td>
                    <input type="number" name="available_units" id="available_units" value="<?php echo htmlspecialchars($available_units); ?>">
                    <div class="error"><?php echo $units_err; ?></div>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input type="submit" value="Add Product"></td>
            </tr>
        </table>
    </form>

    <h3>Current Products</h3>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Price ($)</th>
                <th>Available Units</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="productList">
            <?php
            if ($products && $products->num_rows > 0) {
                while ($row = $products->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['productID']}</td>";
                    echo "<td>{$row['prName']}</td>";
                    echo "<td>{$row['prCategory']}</td>";
                    echo "<td>{$row['prPrice']}</td>";
                    echo "<td>{$row['prUnits']}</td>";
                    echo "<td>
                            <button onclick=\"window.location.href='updateProduct.php?productID={$row['productID']}'\">Update</button>
                            <button onclick=\"window.location.href='deleteProduct.php?productID={$row['productID']}'\">Delete</button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No products found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="../view/sell2.js"></script>
</body>
</html>
