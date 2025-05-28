<?php
include '../model/db.php';

$fullname_error = "";
$username_error = "";
$password_error = "";
$email_error = "";
$phone_error = "";
$address_error = "";
$payment_error = "";
$dob_error = "";
$gender_error = "";

if (isset($_POST["submit"])) {
    $valid = true;

    if (empty($_POST['fullname']) || strlen($_POST['fullname']) < 5) {
        $fullname_error = "Full Name must be at least 5 characters.";
        $valid = false;
    }

    if (empty($_POST['username']) || strlen($_POST['username']) < 3) {
        $username_error = "Username must be at least 3 characters.";
        $valid = false;
    }

    if (empty($_POST['password']) || strlen($_POST['password']) < 6) {
        $password_error = "Password must be at least 6 characters.";
        $valid = false;
    }

    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) { 
        $email_error = "Invalid email format.";
        $valid = false;
    }

    if (empty($_POST['phone']) || !preg_match("/^[0-9]{11}$/", $_POST['phone'])) {
        $phone_error = "Phone number must be 11 digits.";
        $valid = false;
    }

    if (empty($_POST['address'])) {
        $address_error = "Delivery address is required.";
        $valid = false;
    }

    if (empty($_POST['payment_method'])) {
        $payment_error = "Please select a payment method.";   
        $valid = false;
    }

    if (empty($_POST['dob'])) {
        $dob_error = "Date of birth is required.";
        $valid = false;
    }

    if (empty($_POST['gender'])) {
        $gender_error = "Please select a gender.";
        $valid = false;
    }

    if ($valid) {
        $fullname = $_POST['fullname'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $payment_method = $_POST['payment_method'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];

        $db = new mydb();
        $conn = $db->openCon();

        $result = $db->registerCustomer($conn, $fullname, $username, $password, $email, $phone, $address, $payment_method, $dob, $gender);

        if ($result) {
            echo "<p style='color:green; text-align:center;'>Registration successful!</p>";
        } else {
            echo "<p style='color:red; text-align:center;'>Error during registration. Please try again.</p>";
        }

        $db->closeCon($conn);
    } else {
        echo "<p style='color:red; text-align:center;'>Correct the errors and try again.</p>";
    }
}
?>
