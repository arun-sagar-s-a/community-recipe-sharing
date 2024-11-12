/* 
Name: Arun Sagar Sundahally Ashok
ID: 041139362
This file holds the form validation script.
*/

const validate = () => {
    // Remove any previous errors and styles.
    clearErrors();
    // default assumption, form is valid
    let isValid = true;
    

   
    // Validate Login: should be mandatory, less than 30 char and upon successful validation, converted to lowercase.
    // Check if the login id is empty
    let isValid1 = validateLogin();
    // Validate Password.
    let isValid2 = validatePassword();
    
    isValid = isValid1 && isValid2;

    if (isValid){
        const login = document.getElementById("login");
        login.value = login.value.toLowerCase();   
    }

    return isValid;

};

// Function to set error message and style for a field
function displayError(idSelector, message) {
    const inputField = document.getElementById(idSelector);
    const errorMessage = document.getElementById(idSelector + "Error");

    // Add error class and display message
    inputField.classList.add("error");
    errorMessage.innerText = message;
}

// Function to clear previous error messages and styles
function clearErrors() {
    const errorMessages = document.querySelectorAll(".error-message");
    const inputFields = document.querySelectorAll("input");

    // Clear all error messages
    errorMessages.forEach(message => {
        message.innerText = "";
    });

    // Remove error styles
    inputFields.forEach(field => {
        field.classList.remove("error");
    });
}

function clearError(errorId) {
    document.getElementById(errorId).textContent = "";
    document.getElementById(errorId).classList.remove("error");

}
function validateLogin(){
    const login = document.getElementById("login").value.trim();
    if (login === "") {
        displayError("login", "x userid cannot be empty.");
        return false; // Prevent form submission
    } else if (login.length >= 50 || login.length < 3) {
        displayError("login", "x userid must be between 3 to 50 characters.");
        return false;
    } else {
        clearError("loginError");
        return true;
    }
}
function validatePassword(){
    const password = document.getElementById("pass").value;
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;
    if (password.length < 8) {
        displayError("pass", "x Password must be at least 8 characters.");
        return false; // Prevent form submission
    }
    else if (!passwordPattern.test(password)) {
        displayError("pass", "x Password must contain at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character [no '/' allowed].");
        return false;
    }
    else {
        clearError("passError");
        return true;
    }
}