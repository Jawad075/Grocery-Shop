<?php include "../control/seller4a.php"; ?>
<html>
<head>
    <title>Seller Registration</title>
    <link rel="stylesheet" href="../view/s.css">
</head>
<body>

<h2 id="seller">Seller Registration</h2>
<form action="" method="post">
<table border="1"  >

    <tr>
        <td>Seller Name:</td>
        <td>
            <input type="text" name="seller_name" id="seller_name">
            <span style="color:red"><?php echo $seller_name_error; ?></span>
            <p class="error" id="sellerNameErr"> </p>
        </td>
    </tr>

    <tr>
        <td>Username: (Login Info)</td>
        <td>
            <input type="text" name="username" id="username">
            <span style="color:red"><?php echo $username_error; ?></span>
            <p class="error" id="usernameErr"> </p>
        </td>
    </tr>

    <tr>
        <td>Password: (Login Info)</td>
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
        <td>Store Address:</td>
        <td>
            <input type="text" name="store_address" id="store_address">
            <span style="color:red"><?php echo $store_address_error; ?></span>
            <p class="error" id="storeAddressErr"> </p>
        </td>
    </tr>

    <tr>
        <td>Business Type:</td>
        <td>
            <select name="business_type" id="business_type">
                <option value="grocery">Grocery</option>
                <option value="bakery">Bakery</option>
                <option value="dairy">Dairy</option>
                <option value="beverages">Beverages</option>
            </select>
        </td>
    </tr>

    <tr>
        <td>Accepted Payment Methods:</td>
        <td>
            <input type="checkbox" name="payment_method[]" value="card"> Credit Card  
            <input type="checkbox" name="payment_method[]" value="cash"> Cash  
            <input type="checkbox" name="payment_method[]" value="bank"> Bank Transfer  
            <span style="color:red"><?php echo $payment_method_error; ?></span>
            <p class="error" id="paymentMethodErr"> </p>
        </td>
    </tr>

    <tr>
        <td>Delivery Option:</td>
        <td>
            <input type="radio" name="delivery" value="yes" id="delivery_yes"> Yes  
            <input type="radio" name="delivery" value="no" id="delivery_no"> No  
            <span style="color:red"><?php echo $delivery_error; ?></span>
            <p class="error" id="deliveryErr"> </p>
        </td>
    </tr>

    <tr>
        <td colspan="2" align="center">
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
