<?php
session_start();
include "../control/loginA.php";  
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <title>Login - Grocery Hub</title>
   <link rel="stylesheet" href="../view/login.css">
</head>
<body onload="changeBackgroundColor();">

<h2>Login to Grocery Hub</h2>

<form action="" method="post">
  <table>
    <tr>
      <td><label for="userType">User Type:</label></td>
      <td>
        <select id="userType" name="userType" onchange="changeBackgroundColor();">
          <option value="">Select User Type</option>
          <option value="customer" <?php if($userType == "customer") echo "selected"; ?>>Customer</option>
          <option value="seller" <?php if($userType == "seller") echo "selected"; ?>>Seller</option>
        </select>
        <span style="color:red"><?php echo $userType_error; ?></span>
      </td>
    </tr>

    <tr>
      <td><label for="username">Username:</label></td>
      <td>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
        <span style="color:red"><?php echo $username_error; ?></span>
      </td>
    </tr>

    <tr>
      <td><label for="password">Password:</label></td>
      <td>
        <input type="password" id="password" name="password">
        <span style="color:red"><?php echo $password_error; ?></span>
      </td>
    </tr>

    <tr>
      <td></td>
      <td><input type="submit" name="submit" value="Login"></td>
    </tr>
  </table>
</form>

<div class="registration-buttons">
    <button onclick="window.location.href='seller4.php'">Seller Registration</button>
    <button onclick="window.location.href='customer4.php'">Customer Registration</button>
</div>


<script src="../view/login.js"></script>

</body>
</html>
