document.getElementById("regCustomer").addEventListener("submit", function(event) {
    let isValid = true;

    
    document.getElementById("fullNameErr").innerHTML = "";
    document.getElementById("usernameErr").innerHTML = "";
    document.getElementById("passwordErr").innerHTML = "";
    document.getElementById("emailErr").innerHTML = "";
    document.getElementById("phoneErr").innerHTML = "";

     
    let fullname = document.getElementById("fullname").value;
    if (fullname.trim() === "") {
        document.getElementById("fullNameErr").innerHTML = "Full Name is required.";
        isValid = false;
    }

     
    let username = document.getElementById("username").value;
    if (username.trim().length < 5) {
        document.getElementById("usernameErr").innerHTML = "Username must be at least 5 characters.";
        isValid = false;
    }

   
    let password = document.getElementById("password").value;
    let passPattern = /^(?=.*[A-Z])(?=.*\d).{8,}$/;
    if (!passPattern.test(password)) {
        document.getElementById("passwordErr").innerHTML = "Password must be 8 chars with 1 number & 1 capital letter.";
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
        document.getElementById("phoneErr").innerHTML = "Phone number must be exactly 11 digits.";
        isValid = false;
    }

    
    if (!isValid) {
        event.preventDefault();
    }
});
