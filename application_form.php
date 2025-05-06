<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Check if user is logged in
if (!$auth->isLoggedIn()) {
    $_SESSION['message'] = "Please login to access the application form.";
    $_SESSION['message_type'] = "danger";
    header("Location: index.php");
    exit;
}

// Get current user data
$user = $auth->getCurrentUser();

$pageTitle = "Application Form";
$extraCss = ["assets/css/form.css"];
$extraJs = ["assets/js/validation.js"];

require_once 'includes/header.php';
?>

<h2>Student Application Form</h2>

<div class="application-form">
    <!-- Progress Indicator -->
    <div class="progress-indicator">
        <div class="progress-step step-active">
            <div class="step-number">1</div>
            <div class="step-label">Personal Details</div>
        </div>
        <div class="progress-step">
            <div class="step-number">2</div>
            <div class="step-label">Contact Details</div>
        </div>
        <div class="progress-step">
            <div class="step-number">3</div>
            <div class="step-label">Academic Details</div>
        </div>
    </div>
    
    <form id="application-form" method="POST" action="controllers/application_controller.php">
        <!-- Step 1: Personal Details -->
        <div class="form-step">
            <div class="form-section">
                <h3 class="form-section-title">Personal Details</h3>
                
                <div class="form-group">
                    <label for="full-name" class="required-field">Full Name:</label>
                    <input type="text" id="full-name" name="full_name" value="<?php echo $user['first_name'] . ' ' . $user['last_name']; ?>" required>
                    <div class="form-error"></div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="age" class="required-field">Age:</label>
                            <input type="number" id="age" name="age" min="16" max="99" required>
                            <div class="form-error"></div>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group" id="gender-group">
                            <label class="required-field">Gender:</label>
                            <div class="radio-group">
                                <div class="radio-item">
                                    <input type="radio" id="gender-male" name="gender" value="Male" required>
                                    <label for="gender-male">Male</label>
                                </div>
                                <div class="radio-item">
                                    <input type="radio" id="gender-female" name="gender" value="Female" required>
                                    <label for="gender-female">Female</label>
                                </div>
                                <div class="radio-item">
                                    <input type="radio" id="gender-other" name="gender" value="Other" required>
                                    <label for="gender-other">Other</label>
                                </div>
                            </div>
                            <div class="form-error"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <button type="button" class="btn next-step">Next</button>
            </div>
        </div>
        
        <!-- Step 2: Contact Details -->
        <div class="form-step">
            <div class="form-section">
                <h3 class="form-section-title">Contact Details</h3>
                
                <div class="form-group">
                    <label for="app-email" class="required-field">Email:</label>
                    <input type="email" id="app-email" name="email" value="<?php echo $user['email']; ?>" required>
                    <div class="form-error"></div>
                </div>
                
                <div class="form-group">
                    <label for="phone" class="required-field">Phone Number:</label>
                    <input type="tel" id="phone" name="phone" required>
                    <div class="form-error"></div>
                </div>
                
                <div class="form-group">
                    <label for="family-details">Family Details:</label>
                    <textarea id="family-details" name="family_details" rows="4"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="parent-occupation">Parent's Occupation:</label>
                            <input type="text" id="parent-occupation" name="parent_occupation">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="income-range">Income Range:</label>
                            <select id="income-range" name="income_range">
                                <option value="">Select Income Range</option>
                                <option value="Less than $30,000">Less than $30,000</option>
                                <option value="$30,000 - $50,000">$30,000 - $50,000</option>
                                <option value="$50,000 - $80,000">$50,000 - $80,000</option>
                                <option value="$80,000 - $120,000">$80,000 - $120,000</option>
                                <option value="More than $120,000">More than $120,000</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <button type="button" class="btn prev-step">Previous</button>
                <button type="button" class="btn next-step">Next</button>
            </div>
        </div>
        
        <!-- Step 3: Academic Details -->
        <div class="form-step">
            <div class="form-section">
                <h3 class="form-section-title">Academic Details</h3>
                
                <div class="form-group">
                    <label for="preferred-course" class="required-field">Preferred Course:</label>
                    <select id="preferred-course" name="preferred_course" required>
                        <option value="">Select Course</option>
                        <option value="B.Sc. Computer Science">B.Sc. Computer Science</option>
                        <option value="B.Sc. Mathematics">B.Sc. Mathematics</option>
                        <option value="B.Sc. Physics">B.Sc. Physics</option>
                        <option value="B.Com">B.Com</option>
                        <option value="B.Com Accounting">B.Com Accounting</option>
                        <option value="B.A. English">B.A. English</option>
                        <option value="B.A. Economics">B.A. Economics</option>
                        <option value="B.A. Psychology">B.A. Psychology</option>
                    </select>
                    <div class="form-error"></div>
                </div>
                
                <div class="form-group">
                    <label for="statement" class="required-field">Statement of Purpose:</label>
                    <textarea id="statement" name="statement_of_purpose" rows="8" required></textarea>
                    <div class="form-error"></div>
                    <small>Please write a minimum of 100 characters explaining why you want to study this course and your future goals.</small>
                </div>
            </div>
            
            <div class="form-group">
                <button type="button" class="btn prev-step">Previous</button>
                <button type="submit" class="btn submit-btn">Submit Application</button>
            </div>
        </div>
        
        <!-- Hidden field for user ID -->
        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>