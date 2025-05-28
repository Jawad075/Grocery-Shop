 function changeBackgroundColor() {
    var userType = document.getElementById("userType").value;
    if (userType === "customer") {
        document.body.style.backgroundColor = "cyan";
    } else if (userType === "seller") {
        document.body.style.backgroundColor = "yellow";
    } else {
        document.body.style.backgroundColor = "gray";
    }
}
