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
$error = "";
$success = "";

$query = "SELECT * FROM sellerregistration WHERE sellerID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $sellerID);
$stmt->execute();
$result = $stmt->get_result();
$seller = $result->fetch_assoc();

if (!$seller) {
    $error = "Seller not found.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sellerName = trim($_POST['sellerName']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $storeAddress = trim($_POST['storeAddress']);
    $businessType = trim($_POST['businessType']);
    $paymentMethod = trim($_POST['paymentMethod']);
    $deliveryopt = trim($_POST['deliveryopt']);
    $password = trim($_POST['password']);

    if (empty($sellerName) || empty($email) || empty($phone) || empty($storeAddress) || empty($businessType) || empty($paymentMethod) || empty($deliveryopt)) {
        $error = "Please fill in all fields except password if you don't want to change it.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        if (!empty($password)) {
            $updateQuery = "UPDATE sellerregistration SET sellerName=?, email=?, phone=?, storeAddress=?, businessType=?, paymentMethod=?, deliveryopt=?, password=? WHERE sellerID=?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("ssssssssi", $sellerName, $email, $phone, $storeAddress, $businessType, $paymentMethod, $deliveryopt, $password, $sellerID);
        } else {
            $updateQuery = "UPDATE sellerregistration SET sellerName=?, email=?, phone=?, storeAddress=?, businessType=?, paymentMethod=?, deliveryopt=? WHERE sellerID=?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("sssssssi", $sellerName, $email, $phone, $storeAddress, $businessType, $paymentMethod, $deliveryopt, $sellerID);
        }

        if ($stmt->execute()) {
            $success = "Profile updated successfully.";
            $stmt = $conn->prepare("SELECT * FROM sellerregistration WHERE sellerID = ?");
            $stmt->bind_param("i", $sellerID);
            $stmt->execute();
            $result = $stmt->get_result();
            $seller = $result->fetch_assoc();
        } else {
            $error = "Error updating profile. Please try again.";
        }
    }
}

$db->closeCon($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Seller Profile</title>
    <link rel="stylesheet" href="../view/sellerProfile.css">
</head>
<body>

<div class="profile-container">
    <h2>Update Seller Profile</h2>

    <?php if ($error): ?>
        <div style="color: red; margin-bottom: 10px;"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div style="color: green; margin-bottom: 10px;"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <table>
            <tr>
                <td><label for="sellerName"><b>Seller Name:</b></label></td>
                <td><input type="text" id="sellerName" name="sellerName" value="<?php echo htmlspecialchars($seller['sellerName']); ?>" required></td>
            </tr>
            <tr>
                <td><label for="username"><b>Username:</b></label></td>
                <td><input type="text" id="username" name="username" value="<?php echo htmlspecialchars($seller['username']); ?>" disabled></td>
            </tr>
            <tr>
                <td><label for="password"><b>New Password:</b></label></td>
                <td><input type="text" id="password" name="password" placeholder="Leave blank to keep current"></td>
            </tr>
            <tr>
                <td><label for="email"><b>Email:</b></label></td>
                <td><input type="email" id="email" name="email" value="<?php echo htmlspecialchars($seller['email']); ?>" required></td>
            </tr>
            <tr>
                <td><label for="phone"><b>Phone Number:</b></label></td>
                <td><input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($seller['phone']); ?>" required></td>
            </tr>
            <tr>
                <td><label for="storeAddress"><b>Store Address:</b></label></td>
                <td><textarea id="storeAddress" name="storeAddress" rows="3" required><?php echo htmlspecialchars($seller['storeAddress']); ?></textarea></td>
            </tr>
            <tr>
                <td><label for="businessType"><b>Business Type:</b></label></td>
                <td><input type="text" id="businessType" name="businessType" value="<?php echo htmlspecialchars($seller['businessType']); ?>" required></td>
            </tr>
            <tr>
                <td><label for="paymentMethod"><b>Accepted Payment Methods:</b></label></td>
                <td><input type="text" id="paymentMethod" name="paymentMethod" value="<?php echo htmlspecialchars($seller['paymentMethod']); ?>" required></td>
            </tr>
            <tr>
                <td><label for="deliveryopt"><b>Delivery Option:</b></label></td>
                <td><input type="text" id="deliveryopt" name="deliveryopt" value="<?php echo htmlspecialchars($seller['deliveryopt']); ?>" required></td>
            </tr>
        </table>

        <br>
        <button type="submit">Update Profile</button>
        <button type="button" onclick="window.location.href='sellerProfile.php'">Cancel</button>
    </form>
</div>

</body>
</html>
