<?php
if(isset($_POST['btnlogin'])) {
    require_once "config.php";
    $sql = "SELECT * FROM tblaccounts WHERE username = ? AND password = ? AND status = 'ACTIVE'";
    if($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ss", $_POST['txtusername'], $_POST['txtpassword']);
        if(mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result) > 0) {
                $account = mysqli_fetch_array($result, MYSQLI_ASSOC);
                session_start();
                $_SESSION['username'] = $_POST['txtusername'];
                $_SESSION['usertype'] = $account['usertype'];
                header("location:accounts-management.php");
                exit;
            } else {
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            document.getElementById('error-message').innerHTML = 'Incorrect login details or account is inactive.';
                        });
                      </script>";
            }
        } else {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('error-message').innerHTML = 'ERROR on the login statement.';
                    });
                  </script>";
        }
    }
}
?>
?>
<html>
<head>
    <title>Login Page - Equipment Management System</title>
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('https://images.unsplash.com/photo-1501785888041-af3ef285b470') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.3);
            width: 320px;
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 20px;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #bbb;
            border-radius: 6px;
        }
        .login-container input[type="submit"] {
            background: #2e7d32;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }
        .login-container input[type="submit"]:hover {
            background: #1b5e20;
        }
        .password-wrapper {
            position: relative;
            width: 90%;
            margin: 10px auto;
        }
        .password-wrapper input {
            width: 100%;
            padding-right: 35px;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <div id="error-message" style="color:red; margin-bottom:15px;"></div>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <input type="text" name="txtusername" placeholder="Username" required><br>
            
            <div class="password-wrapper">
                <input type="password" name="txtpassword" id="txtpassword" placeholder="Password" required>
                <span id="togglePassword" class="toggle-password">👁️</span>
            </div>

            <input type="submit" name="btnlogin" value="Login">
        </form>
    </div>

    <script>
        const togglePassword = document.getElementById("togglePassword");
        const passwordInput = document.getElementById("txtpassword");

        togglePassword.addEventListener("click", function () {
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);
            this.textContent = type === "password" ? "👁️" : "🙈"; 
        });
    </script>
</body>
</html>