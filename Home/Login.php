<?php
    require 'db.php';
    $message = "";
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        $user = $_POST['username'];
        $pass =  $_POST['password'];
        
        $stmt = $pdo->prepare("SELECT password FROM admins WHERE username = :user");
        $stmt->bindParam(":user", $user, PDO::PARAM_STR);
        $stmt->execute();
        $db_pass = $stmt->fetchColumn();

        if($db_pass != null)
        {
            if($pass == $db_pass)
            {
                $message = "Login successful";
                $toastClass = "bg-success";
                session_start();
                $_SESSION['user'] = $user;
                header("Location: Admin.php");
                exit();
            }
            else {
                $message = "Incorrect password";
                $toastClass = "bg-danger";
            }
        }else {
            $message = "Username not found";
            $toastClass = "bg-warning";
        }
    
    $stmt = null;
    $pdo = null;
    
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Themed Login Page</title>
    <link rel = "stylesheet" href = "Login.css">
    <!-- Google Fonts for better typography -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>


        </style>
</head>
<body>

    <?php if ($message): ?>
            <div class="toast align-items-center text-white 
            <?php echo $toastClass; ?> border-0" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo $message; ?>
                    </div>
                </div>
            </div>
    <?php endif; ?>

    <div class = "login-wrapper">
    <div class="login-container">
        <h1>Login</h1>
        <form method="POST" action="Login.php">
            <!-- Username input -->
            <input type="text" name="username"  placeholder="Username" class="login-input" required>
            
            <!-- Password input -->
            <input type="password" name="password"  placeholder="Password" class="login-input" required><br>
            
            <!-- Login button -->
            <button type="submit" class="login-button">Login</button>
        </form>

      
    </div>
    </div>
</body>
</html>