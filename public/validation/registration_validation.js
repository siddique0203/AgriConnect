function toggleNid() {
  let userType = document.getElementById("userType").value;
  let nidField = document.getElementById("nidField");
  nidField.style.display = userType === "farmer" ? "block" : "none";
}

// Function to display an error message
function displayError(id, message) {
  const errorElement = document.getElementById(id);
  if (errorElement) {
    errorElement.textContent = message;
    errorElement.style.color = "red";
  }
}

// Function to clear an error message
function clearError(id) {
  const errorElement = document.getElementById(id);
  if (errorElement) {
    errorElement.textContent = "";
  }
}

// Validate the registration form
function validateForm() {
  // Clear all previous error messages
  clearError("userId-error");
  clearError("name-error");
  clearError("email-error");
  clearError("phone-error");
  clearError("password-error");
  clearError("userType-error");
  clearError("address-error");
  clearError("nid-error");

  let userId = document.getElementById("userId").value.trim();
  let name = document.getElementById("name").value.trim();
  let email = document.getElementById("email").value.trim();
  let phone = document.getElementById("phone").value.trim();
  let password = document.getElementById("password").value;
  let userType = document.getElementById("userType").value;
  let address = document.getElementById("address").value.trim();
  let nid = document.getElementById("nid").value.trim();

  let isValid = true;

  // User ID: alphanumeric, min 4 chars
  if (!/^[a-zA-Z0-9]{4,}$/.test(userId)) {
    displayError(
      "userId-error",
      "User ID must be at least 4 characters (letters and numbers only)."
    );
    isValid = false;
  }

  // Name: letters and spaces only
  if (!/^[a-zA-Z ]+$/.test(name)) {
    displayError("name-error", "Name must contain only letters and spaces.");
    isValid = false;
  }

  // Email validation
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    displayError("email-error", "Enter a valid email address.");
    isValid = false;
  }

  // Phone: 10–15 digits
  if (!/^[0-9]{10,15}$/.test(phone)) {
    displayError("phone-error", "Phone number must be 10-15 digits.");
    isValid = false;
  }

  // Password: min 6 chars, must contain both letters and numbers
  if (!/^(?=.*[a-zA-Z])(?=.*[0-9]).{6,}$/.test(password)) {
    displayError(
      "password-error",
      "Password must be at least 6 characters and include both letters and numbers."
    );
    isValid = false;
  }

  // User type must be selected
  if (userType === "") {
    displayError("userType-error", "Please select a User Type.");
    isValid = false;
  }

  // Address: minimum 5 characters
  if (address.length < 5) {
    displayError(
      "address-error",
      "Address must be at least 5 characters long."
    );
    isValid = false;
  }

  // NID required if farmer
  if (userType === "farmer") {
    if (!/^[0-9]{10,17}$/.test(nid)) {
      displayError("nid-error", "NID must be 10-17 digits.");
      isValid = false;
    }
  }

  return isValid;
}
