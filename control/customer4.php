<?php include "../control/customer4a.php"; ?>
<html>
<head>
    <title>Customer Registration</title>
    <link rel="stylesheet" href="../view/c.css">
</head>
<body>

<h2 id="customer">Customer Registration</h2>
<form action="" method="post">
<table border="1">

    <tr>
        <td>Full Name:</td>
        <td>
            <input type="text" name="fullname" id="fullname">
            <span style="color:red"><?php echo $fullname_error; ?></span>
            <p class="error" id="fullNameErr"></p>
        </td>
    </tr>

    <tr>
        <td>Username:</td>
        <td>
            <input type="text" name="username" id="username">
            <span style="color:red"><?php echo $username_error; ?></span>
            <p class="error" id="usernameErr"> </p>
        </td>
    </tr>

    <tr>
        <td>Password:</td>
        <td>
            <input type="password" name="password" id="password">
            <span style="color:red"><?php echo $password_error; ?></span>
            <p class="error" id="passwordErr"> </p>
        </td>
    </tr>

    <tr>
        <td>Email:</td>
        <td>
            <input type="email" name="email" id="email">
            <span style="color:red"><?php echo $email_error; ?></span>
            <p class="error" id="emailErr"> </p>
        </td>
    </tr>

    <tr>
        <td>Phone Number:</td>
        <td>
            <input type="tel" name="phone" id="phone">
            <span style="color:red"><?php echo $phone_error; ?></span>
            <p class="error" id="phoneErr"> </p>
        </td>
    </tr>

    <tr>
        <td>Delivery Address:</td>
        <td>
            <input type="text" name="address" id="address">
            <span style="color:red"><?php echo $address_error; ?></span>
            <p class="error" id="addressErr"> </p>
        </td>
    </tr>

    <tr>
        <td>Payment Method:</td>
        <td>
            <select name="payment_method" id="payment_method">
                <option value="">Select Payment Method</option>
                <option value="card">Credit Card</option>
                <option value="cash">Cash on Delivery</option>
                <option value="paypal">PayPal</option>
            </select>
            <span style="color:red"><?php echo $payment_error; ?></span>
            <p class="error" id="paymentMethodErr"> </p>
        </td>
    </tr>

    <tr>
        <td>Date of Birth:</td>
        <td>
            <input type="date" name="dob" id="dob">
            <span style="color:red"><?php echo $dob_error; ?></span>
            <p class="error" id="dobErr"> </p>
        </td>
    </tr>

    <tr>
        <td>Gender:</td>
        <td>
            <select name="gender" id="gender">
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
            <span style="color:red"><?php echo $gender_error; ?></span>
            <p class="error" id="genderErr"> </p>
        </td>
    </tr>

    <tr>
        <td colspan="2" align="right">
            <table width="100%">
                <tr>
                    <td align="left">
                        <input type="button" value="Login" onclick="window.location.href='login.php'" class="left-align-btn">
                    </td>
                    <td align="right">
                        <input type="submit" name="submit" value="Register">
                    </td>
                </tr>
            </table>
        </td>
    </tr>

</table>
</form>

</body>
</html>
