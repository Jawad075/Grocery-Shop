<?php
include "../model/db.php";

$seller_name_error = "";
$username_error = "";
$password_error = "";
$email_error = "";
$phone_error = "";
$store_address_error = "";
$payment_method_error = "";
$delivery_error = "";

if (isset($_POST["submit"])) {
    $valid = true;

    if (empty($_POST['seller_name']) || strlen($_POST['seller_name']) < 5) {
        $seller_name_error = "Seller Name must be at least 5 characters.";
        $valid = false;
    }

    if (empty($_POST['username']) || strlen($_POST['username']) < 5) {
        $username_error = "Username must be at least 5 characters.";
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

    if (empty($_POST['store_address'])) {
        $store_address_error = "Store address is required.";
        $valid = false;
    }

    if (empty($_POST['payment_method'])) {
        $payment_method_error = "Select at least one payment method.";
        $valid = false;
    }

    if (empty($_POST['delivery'])) {
        $delivery_error = "Please select a delivery option.";
        $valid = false;
    }

    if ($valid) {
        $db = new mydb();
        $conn = $db->openCon();

        $seller_name = $_POST['seller_name'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $store_address = $_POST['store_address'];
        $business_type = $_POST['business_type'];
        $payment_method = implode(",", $_POST['payment_method']);
        $delivery = $_POST['delivery'];

        $result = $db->registerSeller($conn, $seller_name, $username, $password, $email, $phone, $store_address, $business_type, $payment_method, $delivery);

        if ($result) {
            echo "<p style='color:white; text-align:center;'>Seller registration successful!</p>";
        } else {
            echo "<p style='color:red; text-align:center;'>Error during registration. Please try a different username or email.</p>";
        }

        $db->closeCon($conn);
    }
}
?>
