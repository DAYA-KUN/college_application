// Form validation functions
document.addEventListener('DOMContentLoaded', function() {
    // Login form validation
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Username validation
            const username = document.getElementById('login-username');
            if (!username.value.trim()) {
                showError(username, 'Username is required');
                isValid = false;
            } else {
                hideError(username);
            }
            
            // Password validation
            const password = document.getElementById('login-password');
            if (!password.value.trim()) {
                showError(password, 'Password is required');
                isValid = false;
            } else {
                hideError(password);
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // Registration form validation
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // First name validation
            const firstName = document.getElementById('first-name');
            if (!firstName.value.trim()) {
                showError(firstName, 'First name is required');
                isValid = false;
            } else {
                hideError(firstName);
            }
            
            // Last name validation
            const lastName = document.getElementById('last-name');
            if (!lastName.value.trim()) {
                showError(lastName, 'Last name is required');
                isValid = false;
            } else {
                hideError(lastName);
            }
            
            // Email validation
            const email = document.getElementById('email');
            if (!email.value.trim()) {
                showError(email, 'Email is required');
                isValid = false;
            } else if (!isValidEmail(email.value)) {
                showError(email, 'Please enter a valid email address');
                isValid = false;
            } else {
                hideError(email);
            }
            
            // Password validation
            const password = document.getElementById('register-password');
            if (!password.value.trim()) {
                showError(password, 'Password is required');
                isValid = false;
            } else if (password.value.length < 6) {
                showError(password, 'Password must be at least 6 characters');
                isValid = false;
            } else {
                hideError(password);
            }
            
            // Confirm password validation
            const confirmPassword = document.getElementById('confirm-password');
            if (!confirmPassword.value.trim()) {
                showError(confirmPassword, 'Please confirm your password');
                isValid = false;
            } else if (confirmPassword.value !== password.value) {
                showError(confirmPassword, 'Passwords do not match');
                isValid = false;
            } else {
                hideError(confirmPassword);
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // Application form validation
    const applicationForm = document.getElementById('application-form');
    if (applicationForm) {
        applicationForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Full name validation
            const fullName = document.getElementById('full-name');
            if (!fullName.value.trim()) {
                showError(fullName, 'Full name is required');
                isValid = false;
            } else {
                hideError(fullName);
            }
            
            // Age validation
            const age = document.getElementById('age');
            if (!age.value.trim()) {
                showError(age, 'Age is required');
                isValid = false;
            } else if (isNaN(age.value) || age.value < 16 || age.value > 99) {
                showError(age, 'Please enter a valid age between 16 and 99');
                isValid = false;
            } else {
                hideError(age);
            }
            
            // Gender validation
            const gender = document.querySelector('input[name="gender"]:checked');
            const genderGroup = document.getElementById('gender-group');
            if (!gender) {
                showError(genderGroup, 'Please select your gender');
                isValid = false;
            } else {
                hideError(genderGroup);
            }
            
            // Email validation
            const email = document.getElementById('app-email');
            if (!email.value.trim()) {
                showError(email, 'Email is required');
                isValid = false;
            } else if (!isValidEmail(email.value)) {
                showError(email, 'Please enter a valid email address');
                isValid = false;
            } else {
                hideError(email);
            }
            
            // Phone validation
            const phone = document.getElementById('phone');
            if (!phone.value.trim()) {
                showError(phone, 'Phone number is required');
                isValid = false;
            } else if (!isValidPhone(phone.value)) {
                showError(phone, 'Please enter a valid phone number');
                isValid = false;
            } else {
                hideError(phone);
            }
            
            // Preferred course validation
            const course = document.getElementById('preferred-course');
            if (course.value === "") {
                showError(course, 'Please select your preferred course');
                isValid = false;
            } else {
                hideError(course);
            }
            
            // Statement of purpose validation
            const sop = document.getElementById('statement');
            if (!sop.value.trim()) {
                showError(sop, 'Statement of purpose is required');
                isValid = false;
            } else if (sop.value.trim().length < 100) {
                showError(sop, 'Statement of purpose must be at least 100 characters');
                isValid = false;
            } else {
                hideError(sop);
            }
            
            if (!isValid) {
                e.preventDefault();
                // Scroll to the first error
                const firstError = document.querySelector('.form-group.invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }
});

// Utility functions
function showError(input, message) {
    const formGroup = input.closest('.form-group');
    formGroup.classList.add('invalid');
    const errorElement = formGroup.querySelector('.form-error');
    if (errorElement) {
        errorElement.textContent = message;
    }
}

function hideError(input) {
    const formGroup = input.closest('.form-group');
    formGroup.classList.remove('invalid');
}

function isValidEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function isValidPhone(phone) {
    const re = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/;
    return re.test(String(phone));
}