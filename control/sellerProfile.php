<?php
session_start();

if (!isset($_SESSION['userID']) || $_SESSION['userType'] != 'seller') {
    header("Location: login.php");
    exit();
}

include "../model/db.php";
$db = new mydb();
$conn = $db->openCon();

$sellerID = $_SESSION['userID'];

$query = "SELECT * FROM sellerregistration WHERE sellerID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $sellerID);
$stmt->execute();
$result = $stmt->get_result();
$seller = $result->fetch_assoc();

$db->closeCon($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller Profile Information</title>
    <link rel="stylesheet" href="../view/sellerProfile.css">
</head>
<body>

<div class="profile-container">
    <h2>Seller Profile</h2>

    <table>
        <tr>
            <td><b>Seller Name:</b></td>
            <td><?php echo htmlspecialchars($seller['sellerName']); ?></td>
        </tr>
        <tr>
            <td><b>Username:</b></td>
            <td><?php echo htmlspecialchars($seller['username']); ?></td>
        </tr>
        <tr>
            <td><b>Password:</b></td>
            <td>********</td>
        </tr>
        <tr>
            <td><b>Email:</b></td>
            <td><?php echo htmlspecialchars($seller['email']); ?></td>
        </tr>
        <tr>
            <td><b>Phone Number:</b></td>
            <td><?php echo htmlspecialchars($seller['phone']); ?></td>
        </tr>
        <tr>
            <td><b>Store Address:</b></td>
            <td><?php echo htmlspecialchars($seller['storeAddress']); ?></td>
        </tr>
        <tr>
            <td><b>Business Type:</b></td>
            <td><?php echo htmlspecialchars($seller['businessType']); ?></td>
        </tr>
        <tr>
            <td><b>Accepted Payment Methods:</b></td>
            <td><?php echo htmlspecialchars($seller['paymentMethod']); ?></td>
        </tr>
        <tr>
            <td><b>Delivery Option:</b></td>
            <td><?php echo htmlspecialchars($seller['deliveryopt']); ?></td>
        </tr>
    </table>

    <br>
    <button onclick="window.location.href='seller2.php'">Back to Dashboard</button>
    <button onclick="window.location.href='logout.php'">Logout</button>
    <button onclick="window.location.href='sellerProfileUpdate.php'" style="margin-left: 30px;">Edit</button>
</div>

</body>
</html>
