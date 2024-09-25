function validateForm() {

    const name = document.getElementById('name').value;
    const password = document.getElementById('password').value;
    const phoneNo = document.getElementById('phoneNumber').value;

    if (name.length < 2) {
        alert("Enter a valid name!");
        return false;
    }

    if (password.length < 8) {
        alert("Password must be at least 8 characters long.");
        return false;
    }

    if (phoneNo.length != 10){
        alert("Enter a valid phone number!");
        return false;
    }

    return true;
    
}
