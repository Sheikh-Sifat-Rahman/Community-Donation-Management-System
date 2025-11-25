<?php
$current_page = 'login';
$page_title = 'Login';
require_once '../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                
                header('Location: ' . SITE_URL . '/pages/dashboard.php');
                exit();
            } else {
                $error = 'Invalid email or password';
            }
        } else {
            $error = 'Invalid email or password';
        }
    }
}
?>

<style>
.auth-page-wrapper {
    min-height: calc(100vh - 200px);
    background: linear-gradient(rgba(107, 74, 142, 0.85), rgba(45, 27, 78, 0.85)), url('../assets/images/volunteer.jpg');
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
    color: #6b4a8e;
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
    border-color: #6b4a8e;
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

.btn-login {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #6b4a8e, #8e6bb4);
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(107, 74, 142, 0.3);
}

.auth-footer {
    text-align: center;
    margin-top: 20px;
    color: #666;
    font-size: 14px;
}

.auth-footer a {
    color: #6b4a8e;
    text-decoration: none;
    font-weight: 600;
}

.auth-footer a:hover {
    text-decoration: underline;
}

.forgot-password {
    text-align: right;
    margin-top: -10px;
    margin-bottom: 20px;
}

.forgot-password a {
    color: #6b4a8e;
    text-decoration: none;
    font-size: 13px;
}

.forgot-password a:hover {
    text-decoration: underline;
}
</style>

<div class="auth-page-wrapper">
    <div class="auth-container">
        <div class="auth-header">
            <h2>Welcome Back</h2>
            <p>Login to your account</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <div class="forgot-password">
                <a href="#">Forgot Password?</a>
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="<?php echo SITE_URL; ?>/pages/register.php">Register here</a>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
