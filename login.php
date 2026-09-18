<?php
session_start();
define('JEWELLERY_ACCESS', true);

require_once 'db_config.php';

$error = '';
$success = '';
$show_register = isset($_GET['register']) ? true : false;

    // Handle Login
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        $conn = getDBConnection();
        if ($conn) {
            $stmt = $conn->prepare("SELECT id, full_name, email, password, role FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                if (password_verify($password, $row['password'])) {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['user_name'] = $row['full_name'];
                    $_SESSION['user_email'] = $row['email'];
                    $_SESSION['user_role'] = $row['role'];
                    
                    // Redirect admin users to admin panel
                    if ($row['role'] === 'admin') {
                        header('Location: admin/dashboard.php');
                        exit;
                    }
                    
                    header('Location: index.php');
                    exit;
                } else {
                    $error = 'Invalid email or password.';
                }
            } else {
                $error = 'No account found with this email.';
            }
            $stmt->close();
        } else {
            $error = 'Database connection failed. Please try again.';
        }
    }
}

// Handle Registration
if (isset($_POST['register'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Please fill in all required fields.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $conn = getDBConnection();
        if ($conn) {
            // Check if email already exists
            $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $check->bind_param("s", $email);
            $check->execute();
            $check->store_result();
            
            if ($check->num_rows > 0) {
                $error = 'An account with this email already exists.';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (full_name, email, phone, password) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $full_name, $email, $phone, $hashed_password);
                
                if ($stmt->execute()) {
                    $success = 'Account created successfully! You can now login.';
                    $show_register = false;
                } else {
                    $error = 'Registration failed. Please try again.';
                }
                $stmt->close();
            }
            $check->close();
        } else {
            $error = 'Database connection failed. Please try again.';
        }
    }
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Login or create your PASVI Jewellery account for a personalized shopping experience.">
<title>Login | PASVI Jewellery</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    min-height: 100vh;
    background: linear-gradient(135deg, #0d0203 0%, #1a0a0c 30%, #2d0d10 60%, #1a0a0c 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    overflow-x: hidden;
}

/* Floating Gold Particles */
.particles-bg {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    overflow: hidden;
    z-index: 0;
}

.particle-gold {
    position: absolute;
    width: 6px;
    height: 6px;
    background: #d4af37;
    border-radius: 50%;
    animation: floatGold 8s infinite ease-in-out;
    opacity: 0.4;
    box-shadow: 0 0 10px rgba(212, 175, 55, 0.3);
}

.particle-gold:nth-child(1) { top: 5%; left: 10%; animation-delay: 0s; width: 4px; height: 4px; }
.particle-gold:nth-child(2) { top: 15%; left: 85%; animation-delay: 1.2s; width: 8px; height: 8px; }
.particle-gold:nth-child(3) { top: 40%; left: 5%; animation-delay: 2.4s; width: 5px; height: 5px; }
.particle-gold:nth-child(4) { top: 60%; left: 90%; animation-delay: 0.6s; width: 6px; height: 6px; }
.particle-gold:nth-child(5) { top: 80%; left: 15%; animation-delay: 3s; width: 4px; height: 4px; }
.particle-gold:nth-child(6) { top: 25%; left: 50%; animation-delay: 1.8s; width: 7px; height: 7px; }
.particle-gold:nth-child(7) { top: 70%; left: 70%; animation-delay: 4s; width: 3px; height: 3px; }
.particle-gold:nth-child(8) { top: 90%; left: 40%; animation-delay: 0.3s; width: 5px; height: 5px; }
.particle-gold:nth-child(9) { top: 45%; left: 80%; animation-delay: 2s; width: 4px; height: 4px; }
.particle-gold:nth-child(10) { top: 10%; left: 30%; animation-delay: 3.6s; width: 6px; height: 6px; }
.particle-gold:nth-child(11) { top: 50%; left: 20%; animation-delay: 1.5s; width: 3px; height: 3px; }
.particle-gold:nth-child(12) { top: 85%; left: 60%; animation-delay: 2.8s; width: 5px; height: 5px; }
.particle-gold:nth-child(13) { top: 35%; left: 95%; animation-delay: 0.9s; width: 4px; height: 4px; }
.particle-gold:nth-child(14) { top: 65%; left: 45%; animation-delay: 4.2s; width: 7px; height: 7px; }
.particle-gold:nth-child(15) { top: 75%; left: 25%; animation-delay: 1.1s; width: 3px; height: 3px; }

@keyframes floatGold {
    0%, 100% { transform: translateY(0) scale(1); opacity: 0.4; }
    50% { transform: translateY(-50px) scale(1.8); opacity: 0.9; }
}

/* Main Container */
.auth-container {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 440px;
}

/* Brand Logo */
.auth-brand {
    text-align: center;
    margin-bottom: 30px;
}

.auth-brand .brand-icon {
    font-size: 40px;
    color: #d4af37;
    display: block;
    margin-bottom: 5px;
    animation: glowPulse 3s ease-in-out infinite;
}

@keyframes glowPulse {
    0%, 100% { text-shadow: 0 0 20px rgba(212, 175, 55, 0.3); }
    50% { text-shadow: 0 0 40px rgba(212, 175, 55, 0.6), 0 0 60px rgba(212, 175, 55, 0.3); }
}

.auth-brand h1 {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    color: #d4af37;
    letter-spacing: 6px;
    font-weight: 700;
}

.auth-brand p {
    color: rgba(255, 255, 255, 0.5);
    font-size: 13px;
    letter-spacing: 1px;
    margin-top: 5px;
}

/* Auth Card */
.auth-card {
    background: rgba(255, 255, 255, 0.04);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(212, 175, 55, 0.15);
    border-radius: 20px;
    padding: 40px 35px;
    box-shadow: 
        0 25px 60px rgba(0, 0, 0, 0.5),
        inset 0 1px 0 rgba(212, 175, 55, 0.1);
}

/* Tabs */
.auth-tabs {
    display: flex;
    margin-bottom: 30px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    padding-bottom: 0;
}

.auth-tab {
    flex: 1;
    text-align: center;
    padding: 12px 0;
    font-size: 15px;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.4);
    cursor: pointer;
    transition: all 0.3s;
    border: none;
    background: none;
    position: relative;
    letter-spacing: 1px;
}

.auth-tab::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 20%;
    width: 60%;
    height: 2px;
    background: linear-gradient(90deg, transparent, #d4af37, transparent);
    transform: scaleX(0);
    transition: transform 0.3s;
}

.auth-tab.active {
    color: #d4af37;
}

.auth-tab.active::after {
    transform: scaleX(1);
}

.auth-tab:hover {
    color: rgba(212, 175, 55, 0.7);
}

/* Alert Messages */
.alert {
    padding: 14px 18px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 400;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    line-height: 1.5;
}

.alert-error {
    background: rgba(255, 71, 87, 0.12);
    border: 1px solid rgba(255, 71, 87, 0.25);
    color: #ff6b81;
}

.alert-success {
    background: rgba(46, 213, 115, 0.12);
    border: 1px solid rgba(46, 213, 115, 0.25);
    color: #2ed573;
}

.alert-icon {
    font-size: 18px;
    flex-shrink: 0;
}

/* Forms */
.auth-form {
    display: none;
}

.auth-form.active {
    display: block;
    animation: fadeSlideIn 0.4s ease;
}

@keyframes fadeSlideIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 7px;
    letter-spacing: 0.5px;
}

.form-group .input-wrapper {
    position: relative;
}

.form-group .input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 16px;
    color: rgba(212, 175, 55, 0.5);
    pointer-events: none;
    transition: color 0.3s;
}

.form-group input {
    width: 100%;
    padding: 14px 16px 14px 46px;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    color: #fff;
    font-size: 14px;
    font-family: 'Poppins', sans-serif;
    outline: none;
    transition: all 0.3s;
}

.form-group input:focus {
    border-color: rgba(212, 175, 55, 0.4);
    background: rgba(255, 255, 255, 0.08);
    box-shadow: 0 0 20px rgba(212, 175, 55, 0.05);
}

.form-group input:focus ~ .input-icon {
    color: #d4af37;
}

.form-group input::placeholder {
    color: rgba(255, 255, 255, 0.2);
    font-weight: 300;
}

.form-group .input-wrapper:focus-within .input-icon {
    color: #d4af37;
}

/* Submit Button */
.btn-submit {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, #d4af37, #c59b27, #b8962b);
    border: none;
    border-radius: 12px;
    color: #1a0a0c;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    letter-spacing: 2px;
    font-family: 'Poppins', sans-serif;
    margin-top: 5px;
    position: relative;
    overflow: hidden;
}

.btn-submit::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn-submit:hover::before {
    left: 100%;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 35px rgba(212, 175, 55, 0.3);
}

.btn-submit:active {
    transform: translateY(0);
}

/* Divider */
.auth-divider {
    display: flex;
    align-items: center;
    margin: 25px 0;
    gap: 15px;
}

.auth-divider::before,
.auth-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: rgba(255, 255, 255, 0.08);
}

.auth-divider span {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.3);
    letter-spacing: 1px;
    text-transform: uppercase;
}

/* Guest Link */
.guest-link {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    color: rgba(255, 255, 255, 0.6);
    text-decoration: none;
    font-size: 14px;
    font-weight: 400;
    transition: all 0.3s;
    background: rgba(255, 255, 255, 0.03);
}

.guest-link:hover {
    border-color: rgba(212, 175, 55, 0.3);
    color: #d4af37;
    background: rgba(212, 175, 55, 0.05);
}

/* Footer links */
.auth-footer-links {
    text-align: center;
    margin-top: 20px;
}

.auth-footer-links a {
    color: rgba(212, 175, 55, 0.6);
    font-size: 13px;
    text-decoration: none;
    transition: color 0.3s;
}

.auth-footer-links a:hover {
    color: #d4af37;
}

/* Already logged in card */
.logged-in-card {
    text-align: center;
    padding: 20px 0;
}

.logged-in-card .avatar {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #d4af37, #c59b27);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 30px;
    color: #1a0a0c;
}

.logged-in-card h3 {
    color: #fff;
    font-size: 20px;
    font-weight: 500;
    margin-bottom: 5px;
}

.logged-in-card p {
    color: rgba(255, 255, 255, 0.5);
    font-size: 14px;
    margin-bottom: 20px;
}

.logged-in-card .btn-group {
    display: flex;
    gap: 12px;
    justify-content: center;
}

.logged-in-card .btn-group a {
    padding: 12px 30px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s;
    font-family: 'Poppins', sans-serif;
}

.logged-in-card .btn-dashboard {
    background: linear-gradient(135deg, #d4af37, #c59b27);
    color: #1a0a0c;
}

.logged-in-card .btn-dashboard:hover {
    box-shadow: 0 8px 25px rgba(212, 175, 55, 0.3);
    transform: translateY(-2px);
}

.logged-in-card .btn-logout {
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: rgba(255, 255, 255, 0.7);
}

.logged-in-card .btn-logout:hover {
    border-color: #ff6b81;
    color: #ff6b81;
}

/* Responsive */
@media(max-width: 480px) {
    .auth-card {
        padding: 30px 20px;
    }
    .auth-brand h1 {
        font-size: 26px;
    }
    .auth-tab {
        font-size: 13px;
        padding: 10px 0;
    }
    .form-group input {
        padding: 12px 14px 12px 42px;
        font-size: 13px;
    }
    .btn-submit {
        padding: 14px;
        font-size: 14px;
    }
}
</style>
</head>
<body>

<!-- Gold Particles Background -->
<div class="particles-bg">
    <div class="particle-gold"></div>
    <div class="particle-gold"></div>
    <div class="particle-gold"></div>
    <div class="particle-gold"></div>
    <div class="particle-gold"></div>
    <div class="particle-gold"></div>
    <div class="particle-gold"></div>
    <div class="particle-gold"></div>
    <div class="particle-gold"></div>
    <div class="particle-gold"></div>
    <div class="particle-gold"></div>
    <div class="particle-gold"></div>
    <div class="particle-gold"></div>
    <div class="particle-gold"></div>
    <div class="particle-gold"></div>
</div>

<div class="auth-container">

    <!-- Brand -->
    <div class="auth-brand">
        <span class="brand-icon">♜</span>
        <h1>PASVI</h1>
        <p>Timeless Elegance • Luxury Since 2026</p>
    </div>

    <?php if(isset($_SESSION['user_id'])): ?>
    
    <!-- Already Logged In -->
    <div class="auth-card">
        <div class="logged-in-card">
            <div class="avatar"><?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?></div>
            <h3>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h3>
            <p>You are signed in as <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
            <div class="btn-group">
                <a href="index.php" class="btn-dashboard">Continue Shopping</a>
                <a href="login.php?logout=1" class="btn-logout">Sign Out</a>
            </div>
        </div>
    </div>

    <?php else: ?>

    <!-- Auth Card -->
    <div class="auth-card">

        <!-- Tabs -->
        <div class="auth-tabs">
            <button class="auth-tab <?php echo !$show_register ? 'active' : ''; ?>" data-tab="login">Sign In</button>
            <button class="auth-tab <?php echo $show_register ? 'active' : ''; ?>" data-tab="register">Create Account</button>
        </div>

        <!-- Error / Success Messages -->
        <?php if($error): ?>
        <div class="alert alert-error">
            <span class="alert-icon">✕</span>
            <span><?php echo htmlspecialchars($error); ?></span>
        </div>
        <?php endif; ?>

        <?php if($success): ?>
        <div class="alert alert-success">
            <span class="alert-icon">✓</span>
            <span><?php echo htmlspecialchars($success); ?></span>
        </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form class="auth-form <?php echo !$show_register ? 'active' : ''; ?>" id="loginForm" method="POST" action="login.php">
            
            <div class="form-group">
                <label>Email Address</label>
                <div class="input-wrapper">
                    <input type="email" name="email" placeholder="your@email.com" required>
                    <span class="input-icon">✉</span>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-wrapper">
                    <input type="password" name="password" placeholder="••••••••" required>
                    <span class="input-icon">🔒</span>
                </div>
            </div>

            <button type="submit" name="login" class="btn-submit">SIGN IN</button>

            <div class="auth-divider">
                <span>Or continue as guest</span>
            </div>

            <a href="index.php" class="guest-link">
                <span>👤</span> Browse as Guest
            </a>

        </form>

        <!-- Register Form -->
        <form class="auth-form <?php echo $show_register ? 'active' : ''; ?>" id="registerForm" method="POST" action="login.php?register=1">
            
            <div class="form-group">
                <label>Full Name</label>
                <div class="input-wrapper">
                    <input type="text" name="full_name" placeholder="Your full name" required>
                    <span class="input-icon">👤</span>
                </div>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <div class="input-wrapper">
                    <input type="email" name="email" placeholder="your@email.com" required>
                    <span class="input-icon">✉</span>
                </div>
            </div>

            <div class="form-group">
                <label>Phone Number (Optional)</label>
                <div class="input-wrapper">
                    <input type="tel" name="phone" placeholder="+91 98765 43210">
                    <span class="input-icon">📞</span>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-wrapper">
                    <input type="password" name="password" placeholder="Min. 6 characters" required minlength="6">
                    <span class="input-icon">🔒</span>
                </div>
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <div class="input-wrapper">
                    <input type="password" name="confirm_password" placeholder="Re-enter password" required minlength="6">
                    <span class="input-icon">✓</span>
                </div>
            </div>

            <button type="submit" name="register" class="btn-submit">CREATE ACCOUNT</button>

            <div class="auth-divider">
                <span>Already have an account?</span>
            </div>

            <div class="auth-footer-links">
                <a href="login.php">Sign in here →</a>
            </div>

        </form>

    </div>

    <?php endif; ?>

    <!-- Footer link -->
    <div style="text-align:center;margin-top:25px;">
        <a href="index.php" style="color:rgba(255,255,255,0.3);text-decoration:none;font-size:13px;transition:color 0.3s;"
           onmouseover="this.style.color='#d4af37'" onmouseout="this.style.color='rgba(255,255,255,0.3)'">
            ← Back to Homepage
        </a>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.auth-tab');
    const forms = document.querySelectorAll('.auth-form');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const target = this.dataset.tab;
            
            // Update tabs
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Update forms
            forms.forEach(form => {
                form.classList.remove('active');
                if ((target === 'login' && form.id === 'loginForm') ||
                    (target === 'register' && form.id === 'registerForm')) {
                    form.classList.add('active');
                }
            });

            // Update URL without reload
            const url = new URL(window.location);
            if (target === 'register') {
                url.searchParams.set('register', '1');
            } else {
                url.searchParams.delete('register');
            }
            window.history.replaceState({}, '', url);
        });
    });

    // Password match validation for registration
    const regForm = document.getElementById('registerForm');
    if (regForm) {
        regForm.addEventListener('submit', function(e) {
            const password = this.querySelector('input[name="password"]');
            const confirm = this.querySelector('input[name="confirm_password"]');
            if (password.value !== confirm.value) {
                e.preventDefault();
                alert('Passwords do not match!');
            }
        });
    }

    // Auto-dismiss alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});
</script>

</body>
</html>
