<?php
session_start();

if (!isset($_SESSION['userID']) || $_SESSION['userType'] !== 'customer') {
    header("Location: login.php");
    exit();
}

include "../model/db.php";

$db = new mydb();
$conn = $db->openCon();

$customerID = $_SESSION['userID'];

if (isset($_GET['delete'])) {
    $productID = $_GET['delete'];

    $deleteQuery = "DELETE FROM inventory WHERE customerID = ? AND productID = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("ii", $customerID, $productID);
    $stmt->execute();
}

$query = "
    SELECT p.prName, p.prCategory, p.prPrice, i.quantity, i.purchaseDate, i.productID
    FROM inventory i
    JOIN products p ON i.productID = p.productID
    WHERE i.customerID = ?
    ORDER BY i.purchaseDate DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customerID);
$stmt->execute();
$result = $stmt->get_result();

$inventory = [];
while ($row = $result->fetch_assoc()) {
    $inventory[] = $row;
}

$db->closeCon($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Inventory</title>
    <link rel="stylesheet" href="../view/inventory.css">
</head>
<body>

<div class="inventory-container">
    <h2>My Purchased Items</h2>

    <?php if (count($inventory) > 0): ?>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Category</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Purchase Date</th>
                <th>Action</th>
            </tr>
            <?php foreach ($inventory as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['prName']); ?></td>
                    <td><?php echo htmlspecialchars($item['prCategory']); ?></td>
                    <td>$<?php echo number_format($item['prPrice'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo date("d M Y, h:i A", strtotime($item['purchaseDate'])); ?></td>
                    <td>
                        <a href="inventory.php?delete=<?php echo $item['productID']; ?>" onclick="return confirm('Are you sure you want to remove this item?');">
                            Remove
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>You have not purchased any items yet.</p>
    <?php endif; ?>

    <div class="back-button-wrapper">
        <a href="customer2.php">
            <button class="back-button">Back to Shop</button>
        </a>
    </div>
</div>

</body>
</html>
