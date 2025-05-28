<?php
class mydb {

     function openCon() {
        $dbhost = "localhost";
        $dbusername = "root";
        $dbpassword = "";
        $dbname = "grocery";

        $connobject = new mysqli($dbhost, $dbusername, $dbpassword, $dbname);

        if ($connobject->connect_error) {
            die("Connection failed: " . $connobject->connect_error);
        }

        return $connobject;
    }

     public function closeCon($conn) {
        $conn->close();
    }

     function registerSeller($conn, $seller_name, $username, $password, $email, $phone, $store_address, $business_type, $payment_method, $delivery) {
        $sql = "INSERT INTO sellerregistration 
                (sellerName, username, password, email, phone, storeAddress, businessType, paymentMethod, deliveryopt)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sssssssss", 
            $seller_name, 
            $username, 
            $password, 
            $email, 
            $phone, 
            $store_address, 
            $business_type, 
            $payment_method, 
            $delivery
        );

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

     function registerCustomer($conn, $fullname, $username, $password, $email, $phone, $address, $payment_method, $dob, $gender) {
        $sql = "INSERT INTO customerregistration 
                (cusName, username, password, email, phone, deliveryAddress, paymentMethod, dob, gender)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sssssssss", 
            $fullname, 
            $username, 
            $password, 
            $email, 
            $phone, 
            $address, 
            $payment_method, 
            $dob, 
            $gender
        );

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

     function verifyLogin($conn, $username, $password, $userType) {
        $table = ($userType == 'seller') ? 'sellerregistration' : 'customerregistration';
        $sql = "SELECT * FROM $table WHERE username = ?";

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die('Error preparing the statement: ' . $conn->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if ($password === $user['password']) {
                return $user;
            }
        }
        return false;
    }

     function addProduct($productID, $prName, $prCategory, $prPrice, $prUnits, $conn) {
        $query = "INSERT INTO products (productID, prName, prCategory, prPrice, prUnits) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die('Error preparing the statement: ' . $conn->error);
        }
        $stmt->bind_param("ssssi", $productID, $prName, $prCategory, $prPrice, $prUnits);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

     function getProductById($productID, $conn) {
        $query = "SELECT * FROM products WHERE productID = ?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die('Error preparing the statement: ' . $conn->error);
        }
        $stmt->bind_param("i", $productID);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();

        return $product;
    }

     function updateProductDetails($productID, $prName, $prCategory, $prPrice, $prUnits, $conn) {
        $sql = "UPDATE products SET prName = ?, prCategory = ?, prPrice = ?, prUnits = ? WHERE productID = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die('Error preparing the statement: ' . $conn->error);
        }
        $stmt->bind_param("ssdii", $prName, $prCategory, $prPrice, $prUnits, $productID);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

     function deleteProduct($productID, $conn) {
        $query = "DELETE FROM products WHERE productID = ?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die('Error preparing the statement: ' . $conn->error);
        }
        $stmt->bind_param("i", $productID);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

     function getAllAvailableProducts($conn) {
        $query = "SELECT * FROM products WHERE prUnits > 0";
        $result = $conn->query($query);
        if ($result === false) {
            die('Error executing the query: ' . $conn->error);
        }
        return $result;
    }

     function getCustomerProfile($userID, $conn) {
        $query = "SELECT * FROM customerregistration WHERE customerID = ?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die('Error preparing the statement: ' . $conn->error);
        }
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $customer = $result->fetch_assoc();
        $stmt->close();

        return $customer;
    }

     function getSellerProfile($userID, $conn) {
        $query = "SELECT * FROM sellerregistration WHERE sellerID = ?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die('Error preparing the statement: ' . $conn->error);
        }
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $seller = $result->fetch_assoc();
        $stmt->close();

        return $seller;
    }

     function addProductToSessionCart($productID, $quantity) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$productID])) {
            $_SESSION['cart'][$productID] += $quantity;
        } else {
            $_SESSION['cart'][$productID] = $quantity;
        }
    }

     function getCartItems() {
        return isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    }

     function addProductToOrder($userID, $productID, $quantity, $conn) {
        $query = "SELECT prUnits FROM products WHERE productID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $productID);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $availableUnits = $product['prUnits'];

        if ($quantity <= $availableUnits) {
            $orderQuery = "INSERT INTO orders (userID, productID, quantity) VALUES (?, ?, ?)";
            $orderStmt = $conn->prepare($orderQuery);
            $orderStmt->bind_param("iii", $userID, $productID, $quantity);
            $orderStmt->execute();
            $orderStmt->close();

            $updateQuery = "UPDATE products SET prUnits = prUnits - ? WHERE productID = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("ii", $quantity, $productID);
            $updateStmt->execute();
            $updateStmt->close();

            return true;
        } else {
            return false;
        }
    }
}
?>
