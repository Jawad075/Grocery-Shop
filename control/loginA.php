<?php
$userType = $username = $password = "";  
$userType_error = $username_error = $password_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $valid = true;

    if (empty($_POST["userType"])) {
        $userType_error = "Please select a user type.";
        $valid = false;
    } else {
        $userType = $_POST["userType"];
    }

    if (empty($_POST["username"])) {
        $username_error = "Username is required.";
        $valid = false;
    } else {
        $username = trim($_POST["username"]);
        if (strlen($username) < 5) {
            $username_error = "Username must be at least 5 characters.";
            $valid = false;
        }
    }

    if (empty($_POST["password"])) {
        $password_error = "Password is required.";
        $valid = false;
    } elseif (strlen($_POST["password"]) < 6) {
        $password_error = "Password must be at least 6 characters.";
        $valid = false;
    } else {
        $password = $_POST["password"];
    }

    if ($valid) {
        include "../model/db.php";
        $db = new mydb();
        $conn = $db->openCon();

        $sql = ($userType === "seller") 
            ? "SELECT * FROM sellerregistration WHERE username = ?" 
            : "SELECT * FROM customerregistration WHERE username = ?";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("DB Error: " . $conn->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if ($password === $user['password']) {
                if ($userType === 'seller' && isset($user['sellerId'])) {
                    $_SESSION['userID'] = $user['sellerId'];
                } elseif ($userType === 'customer' && isset($user['customerID'])) {
                    $_SESSION['userID'] = $user['customerID'];
                } else {
                    $_SESSION['userID'] = null;
                }

                $_SESSION['username'] = $user['username'];
                $_SESSION['userType'] = $userType;

                $redirectPage = ($userType === 'seller') ? 'seller2.php' : 'customer2.php';
                echo "<script>window.location.href='$redirectPage';</script>";
                exit();
            } else {
                $password_error = "Incorrect password!";
            }
        } else {
            $username_error = "No such username found!";
        }

        $stmt->close();
        $db->closeCon($conn);
    }
}
?>
