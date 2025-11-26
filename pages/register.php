<?php
$current_page = 'register';
$page_title = 'Register';
require_once '../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Please fill in all required fields';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long';
    } else {
        // Check if email already exists
        $check_query = "SELECT * FROM users WHERE email = '$email'";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = 'Email already registered';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $insert_query = "INSERT INTO users (name, email, password, phone, role, created_at) 
                           VALUES ('$name', '$email', '$hashed_password', '$phone', 'user', NOW())";
            
            if (mysqli_query($conn, $insert_query)) {
                $success = 'Registration successful! You can now login.';
                // Optionally auto-login
                $_SESSION['user_id'] = mysqli_insert_id($conn);
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_role'] = 'user';
                
                header('Location: ' . SITE_URL . '/pages/dashboard.php');
                exit();
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>

<style>
.auth-page-wrapper {
    min-height: calc(100vh - 200px);
    background: linear-gradient(rgba(255, 107, 107, 0.85), rgba(217, 83, 79, 0.85)), url('../assets/images/donation-2.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
}

.auth-container {
    max-width: 450px;
    width: 100%;
    padding: 40px;
    background: rgba(255, 255, 255, 0.98);
    border-radius: 10px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
}

.auth-header {
    text-align: center;
    margin-bottom: 30px;
}

.auth-header h2 {
    color: #ff6b6b;
    font-size: 32px;
    margin-bottom: 10px;
}

.auth-header p {
    color: #666;
    font-size: 14px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-weight: 500;
}

.form-group label .optional {
    color: #999;
    font-weight: 400;
    font-size: 12px;
}

.form-group input {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 5px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.form-group input:focus {
    outline: none;
    border-color: #ff6b6b;
}

.alert {
    padding: 12px 15px;
    border-radius: 5px;
    margin-bottom: 20px;
    font-size: 14px;
}

.alert-error {
    background-color: #fee;
    color: #c33;
    border: 1px solid #fcc;
}

.alert-success {
    background-color: #efe;
    color: #3c3;
    border: 1px solid #cfc;
}

.btn-register {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #ff6b6b, #ff8e53);
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}

.btn-register:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
}

.auth-footer {
    text-align: center;
    margin-top: 20px;
    color: #666;
    font-size: 14px;
}

.auth-footer a {
    color: #ff6b6b;
    text-decoration: none;
    font-weight: 600;
}

.auth-footer a:hover {
    text-decoration: underline;
}

.password-strength {
    font-size: 12px;
    margin-top: 5px;
    color: #666;
}
</style>

<div class="auth-page-wrapper">
    <div class="auth-container">
        <div class="auth-header">
            <h2>Create Account</h2>
            <p>Join us to make a difference</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" placeholder="Enter your full name" required 
                   value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required
                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="phone">Phone Number <span class="optional">(Optional)</span></label>
            <input type="tel" id="phone" name="phone" placeholder="Enter your phone number"
                   value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Create a password" required>
            <div class="password-strength">Must be at least 6 characters</div>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
        </div>

        <button type="submit" class="btn-register">Create Account</button>
    </form>

    <div class="auth-footer">
        Already have an account? <a href="<?php echo SITE_URL; ?>/pages/login.php">Login here</a>
    </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
