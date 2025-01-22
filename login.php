<?php
session_start();
require_once 'config.php';
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $errors[] = "Both email and password are required";
    } else {
        $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];
                
                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header("Location: admin/dashboard.php");
                } else {
                    header("Location: dashboard.php");
                }
                exit();
            } else {
                $errors[] = "Invalid email or password";
            }
        } else {
            $errors[] = "Invalid email or password";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Library Management System</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: linear-gradient(135deg, #f0f7ff 0%, #ffffff 100%);
        }
        
        .auth-box {
            background: white;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        
        .auth-box h1 {
            text-align: center;
            margin-bottom: 2rem;
            color: var(--primary-color);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            transition: var(--transition);
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        
        .auth-btn {
            width: 100%;
            padding: 0.75rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .auth-btn:hover {
            background: var(--primary-dark);
        }
        
        .auth-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-secondary);
            text-decoration: none;
        }
        
        .auth-link:hover {
            color: var(--primary-color);
        }
        
        .error-list {
            background: #fee2e2;
            border: 1px solid #ef4444;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #b91c1c;
        }
        
        .error-list ul {
            margin: 0;
            padding-left: 1.5rem;
        }
        
        .success-message {
            background: #ecfdf5;
            border: 1px solid #10b981;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #047857;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h1>Login</h1>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="success-message">
                    <?php 
                        echo htmlspecialchars($_SESSION['success']);
                        unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($errors)): ?>
                <div class="error-list">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="auth-btn">Login</button>
            </form>
            
            <a href="register.php" class="auth-link">Don't have an account? Register</a>
        </div>
    </div>
</body>
</html>