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
$error = "";
$success = "";

$query = "SELECT * FROM customerregistration WHERE customerID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    $error = "User not found.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cusName = trim($_POST['cusName']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $deliveryAddress = trim($_POST['deliveryAddress']);
    $paymentMethod = trim($_POST['paymentMethod']);
    $dob = trim($_POST['dob']);
    $gender = trim($_POST['gender']);
    $password = trim($_POST['password']);

    if (empty($cusName) || empty($email) || empty($phone) || empty($deliveryAddress) || empty($paymentMethod) || empty($dob) || empty($gender)) {
        $error = "Please fill in all fields except password if you don't want to change it.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        if (!empty($password)) {
            $updateQuery = "UPDATE customerregistration SET cusName=?, email=?, phone=?, deliveryAddress=?, paymentMethod=?, dob=?, gender=?, password=? WHERE customerID=?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("ssssssssi", $cusName, $email, $phone, $deliveryAddress, $paymentMethod, $dob, $gender, $password, $userID);
        } else {
            $updateQuery = "UPDATE customerregistration SET cusName=?, email=?, phone=?, deliveryAddress=?, paymentMethod=?, dob=?, gender=? WHERE customerID=?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("sssssssi", $cusName, $email, $phone, $deliveryAddress, $paymentMethod, $dob, $gender, $userID);
        }

        if ($stmt->execute()) {
            $success = "Profile updated successfully.";
            $stmt = $conn->prepare("SELECT * FROM customerregistration WHERE customerID = ?");
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
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
    <title>Update Customer Profile</title>
    <link rel="stylesheet" href="../view/cusProfile.css">
</head>
<body>

<div class="profile-container">
    <h2>Update Profile</h2>

    <?php if ($error): ?>
        <div style="color: red; margin-bottom: 10px;"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div style="color: green; margin-bottom: 10px;"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <table class="profile-table">
            <tr>
                <td><label for="cusName"><b>Full Name:</b></label></td>
                <td><input type="text" id="cusName" name="cusName" value="<?php echo htmlspecialchars($user['cusName']); ?>" required></td>
            </tr>
            <tr>
                <td><label for="username"><b>Username:</b></label></td>
                <td><input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled></td>
            </tr>
            <tr>
                <td><label for="password"><b>New Password:</b></label></td>
                <td><input type="text" id="password" name="password" placeholder="Leave blank to keep current"></td>
            </tr>
            <tr>
                <td><label for="email"><b>Email:</b></label></td>
                <td><input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required></td>
            </tr>
            <tr>
                <td><label for="phone"><b>Phone Number:</b></label></td>
                <td><input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required></td>
            </tr>
            <tr>
                <td><label for="deliveryAddress"><b>Delivery Address:</b></label></td>
                <td><textarea id="deliveryAddress" name="deliveryAddress" rows="3" required><?php echo htmlspecialchars($user['deliveryAddress']); ?></textarea></td>
            </tr>
            <tr>
                <td><label for="paymentMethod"><b>Payment Method:</b></label></td>
                <td><input type="text" id="paymentMethod" name="paymentMethod" value="<?php echo htmlspecialchars($user['paymentMethod']); ?>" required></td>
            </tr>
            <tr>
                <td><label for="dob"><b>Date of Birth:</b></label></td>
                <td><input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>" required></td>
            </tr>
            <tr>
                <td><label for="gender"><b>Gender:</b></label></td>
                <td>
                    <select id="gender" name="gender" required>
                        <option value="">Select</option>
                        <option value="Male" <?php if ($user['gender'] === "Male") echo "selected"; ?>>Male</option>
                        <option value="Female" <?php if ($user['gender'] === "Female") echo "selected"; ?>>Female</option>
                        <option value="Other" <?php if ($user['gender'] === "Other") echo "selected"; ?>>Other</option>
                    </select>
                </td>
            </tr>
        </table>

        <br>
        <button type="submit">Update Profile</button>
        <button type="button" onclick="window.location.href='customerProfile.php'">Back</button>
    </form>
</div>

</body>
</html>
