document.getElementById("regSeller").addEventListener("submit", function(event) {
    let isValid = true;

    
    document.getElementById("sellerNameErr").innerHTML = "";
    document.getElementById("usernameErr").innerHTML = "";
    document.getElementById("passwordErr").innerHTML = "";
    document.getElementById("emailErr").innerHTML = "";
    document.getElementById("phoneErr").innerHTML = "";

     
    let sellerName = document.getElementById("seller_name").value;
    if (sellerName.trim() === "") {
        document.getElementById("sellerNameErr").innerHTML = "Seller Name is required.";
        isValid = false;
    }

     
    let username = document.getElementById("username").value;
    if (username.trim().length < 6) {
        document.getElementById("usernameErr").innerHTML = "Minimum 6 characters required.";
        isValid = false;
    }

    
    let password = document.getElementById("password").value;
    let passPattern = /^(?=.*[A-Z])(?=.*\d).{6,}$/;
    if (!passPattern.test(password)) {
        document.getElementById("passwordErr").innerHTML = "Must be 6+ characters with 1 number & 1 capital letter.";
        isValid = false;
    }

     
    let email = document.getElementById("email").value;
    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        document.getElementById("emailErr").innerHTML = "Invalid email format.";
        isValid = false;
    }

     let phone = document.getElementById("phone").value;
    let phonePattern = /^\d{11}$/;
    if (!phonePattern.test(phone)) {
        document.getElementById("phoneErr").innerHTML = "Phone must be exactly 11 digits.";
        isValid = false;
    }

     if (!isValid) {
        event.preventDefault();
    }
});
