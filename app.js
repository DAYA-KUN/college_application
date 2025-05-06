// Main application JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Close alert messages
    const alertCloseButtons = document.querySelectorAll('.alert .close');
    if (alertCloseButtons) {
        alertCloseButtons.forEach(button => {
            button.addEventListener('click', function() {
                const alert = button.closest('.alert');
                alert.style.display = 'none';
            });
        });
    }
    
    // Toggle password visibility
    const togglePasswordButtons = document.querySelectorAll('.toggle-password');
    if (togglePasswordButtons) {
        togglePasswordButtons.forEach(button => {
            button.addEventListener('click', function() {
                const passwordInput = document.getElementById(this.getAttribute('data-toggle'));
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    this.textContent = 'Hide';
                } else {
                    passwordInput.type = 'password';
                    this.textContent = 'Show';
                }
            });
        });
    }
    
    // Application form - multi-step functionality
    const applicationForm = document.getElementById('application-form');
    if (applicationForm) {
        const formSteps = document.querySelectorAll('.form-step');
        const progressSteps = document.querySelectorAll('.progress-step');
        const nextButtons = document.querySelectorAll('.next-step');
        const prevButtons = document.querySelectorAll('.prev-step');
        
        let currentStep = 0;
        
        // Initialize form
        showStep(currentStep);
        
        // Next button click
        if (nextButtons) {
            nextButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Validate current step
                    if (validateStep(currentStep)) {
                        currentStep++;
                        showStep(currentStep);
                    }
                });
            });
        }
        
        // Previous button click
        if (prevButtons) {
            prevButtons.forEach(button => {
                button.addEventListener('click', function() {
                    currentStep--;
                    showStep(currentStep);
                });
            });
        }
        
        // Show step function
        function showStep(stepIndex) {
            formSteps.forEach((step, index) => {
                step.style.display = index === stepIndex ? 'block' : 'none';
            });
            
            // Update progress indicator
            progressSteps.forEach((step, index) => {
                if (index < stepIndex) {
                    step.classList.add('step-complete');
                    step.classList.remove('step-active');
                } else if (index === stepIndex) {
                    step.classList.add('step-active');
                    step.classList.remove('step-complete');
                } else {
                    step.classList.remove('step-active', 'step-complete');
                }
            });
            
            // Show/hide prev/next buttons
            const prevButton = document.querySelector('.prev-step');
            const nextButton = document.querySelector('.next-step');
            const submitButton = document.querySelector('.submit-btn');
            
            if (prevButton) {
                prevButton.style.display = stepIndex === 0 ? 'none' : 'inline-block';
            }
            
            if (nextButton && submitButton) {
                if (stepIndex === formSteps.length - 1) {
                    nextButton.style.display = 'none';
                    submitButton.style.display = 'inline-block';
                } else {
                    nextButton.style.display = 'inline-block';
                    submitButton.style.display = 'none';
                }
            }
        }
        
        // Validate step function
        function validateStep(stepIndex) {
            let isValid = true;
            const currentFormStep = formSteps[stepIndex];
            
            // Personal details step
            if (stepIndex === 0) {
                const fullName = currentFormStep.querySelector('#full-name');
                const age = currentFormStep.querySelector('#age');
                const gender = currentFormStep.querySelector('input[name="gender"]:checked');
                const genderGroup = currentFormStep.querySelector('#gender-group');
                
                if (!fullName.value.trim()) {
                    showError(fullName, 'Full name is required');
                    isValid = false;
                } else {
                    hideError(fullName);
                }
                
                if (!age.value.trim()) {
                    showError(age, 'Age is required');
                    isValid = false;
                } else if (isNaN(age.value) || age.value < 16 || age.value > 99) {
                    showError(age, 'Please enter a valid age between 16 and 99');
                    isValid = false;
                } else {
                    hideError(age);
                }
                
                if (!gender) {
                    showError(genderGroup, 'Please select your gender');
                    isValid = false;
                } else {
                    hideError(genderGroup);
                }
            }
            
            // Contact details step
            else if (stepIndex === 1) {
                const email = currentFormStep.querySelector('#app-email');
                const phone = currentFormStep.querySelector('#phone');
                
                if (!email.value.trim()) {
                    showError(email, 'Email is required');
                    isValid = false;
                } else if (!isValidEmail(email.value)) {
                    showError(email, 'Please enter a valid email address');
                    isValid = false;
                } else {
                    hideError(email);
                }
                
                if (!phone.value.trim()) {
                    showError(phone, 'Phone number is required');
                    isValid = false;
                } else if (!isValidPhone(phone.value)) {
                    showError(phone, 'Please enter a valid phone number');
                    isValid = false;
                } else {
                    hideError(phone);
                }
            }
            
            // Academic details step
            else if (stepIndex === 2) {
                const course = currentFormStep.querySelector('#preferred-course');
                const sop = currentFormStep.querySelector('#statement');
                
                if (course.value === "") {
                    showError(course, 'Please select your preferred course');
                    isValid = false;
                } else {
                    hideError(course);
                }
                
                if (!sop.value.trim()) {
                    showError(sop, 'Statement of purpose is required');
                    isValid = false;
                } else if (sop.value.trim().length < 100) {
                    showError(sop, 'Statement of purpose must be at least 100 characters');
                    isValid = false;
                } else {
                    hideError(sop);
                }
            }
            
            if (!isValid) {
                // Scroll to the first error
                const firstError = currentFormStep.querySelector('.form-group.invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
            
            return isValid;
        }
    }
});