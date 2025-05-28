<?php
session_start();

if (!isset($_SESSION['userID']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include "../model/db.php";

$db = new mydb();
$conn = $db->openCon();

$userID = $_SESSION['userID'];

$query = "SELECT * FROM customerregistration WHERE customerID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$db->closeCon($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Customer Profile Informations</title>
    <link rel="stylesheet" href="../view/cusProfile.css">
</head>
<body>

<div class="profile-container">
    <h2>Customer Profile</h2>

    <table class="profile-table">
        <tr>
            <td><b>Full Name:</b></td>
            <td><?php echo htmlspecialchars($user['cusName']); ?></td>
        </tr>
        <tr>
            <td><b>Username:</b></td>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
        </tr>
        <tr>
            <td><b>Password:</b></td>
            <td>********</td>
        </tr>
        <tr>
            <td><b>Email:</b></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
        </tr>
        <tr>
            <td><b>Phone Number:</b></td>
            <td><?php echo htmlspecialchars($user['phone']); ?></td>
        </tr>
        <tr>
            <td><b>Delivery Address:</b></td>
            <td><?php echo htmlspecialchars($user['deliveryAddress']); ?></td>
        </tr>
        <tr>
            <td><b>Payment Method:</b></td>
            <td><?php echo htmlspecialchars($user['paymentMethod']); ?></td>
        </tr>
        <tr>
            <td><b>Date of Birth:</b></td>
            <td><?php echo htmlspecialchars($user['dob']); ?></td>
        </tr>
        <tr>
            <td><b>Gender:</b></td>
            <td><?php echo htmlspecialchars($user['gender']); ?></td>
        </tr>
    </table>

    <br>
    <button onclick="window.location.href='customer2.php'">Back to Shop</button>
    <button onclick="window.location.href='logout.php'" style="margin-left: 10px;">Logout</button>
    <button onclick="window.location.href='cusProfileUpdate.php'" style="margin-left: 30px;">Edit</button>
</div>

</body>
</html>
