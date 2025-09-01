<?php
require_once "config.php";
include ("session-checker.php");

if(isset($_POST['btnsubmit'])) {
    $sql = "SELECT * FROM tblaccounts WHERE username = ?";
    if($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $_POST['txtusername']);
        if(mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result) == 0) {
                $sql = "INSERT INTO tblaccounts (username, password, usertype, status, createdby, datecreated) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                if($stmt = mysqli_prepare($link, $sql)) {
                    $status = "ACTIVE";
                    $date = date("d/M/Y");
                    mysqli_stmt_bind_param($stmt, "ssssss",
                        $_POST['txtusername'],
                        $_POST['txtpassword'],
                        $_POST['cmbtype'],
                        $status,
                        $_SESSION['username'],
                        $date
                    );
                    if(mysqli_stmt_execute($stmt)) {
                        header("location: accounts-management.php");
                        exit();
                    } else {
                        echo "<div style='color:red; text-align:center;'>ERROR on inserting account.</div>";
                    }
                }
            } else {
                echo "<div style='color:red; text-align:center;'>ERROR: username already in-use.</div>";
            }
        }
    }
}
?>
<html>
<head>
    <title>Create new account - Equipment Management System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* optional: quick styling kung wala pa sa style.css */
        body {
             margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('jungle.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
        }
        .form-container {
            width: 350px;
            margin: 80px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0,0,0,0.2);
            text-align: center;
        }
        input, select {
            width: 90%;
            padding: 8px;
            margin: 8px 0;
        }
        .password-wrapper {
            position: relative;
            display: inline-block;
            width: 90%;
        }
        .password-wrapper input {
            width: 100%;
            padding-right: 40px;
        }
        .password-wrapper span {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
        }
        input[type="submit"] {
            background: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #45a049;
        }
        a {
            text-decoration: none;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Create Account</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <input type="text" name="txtusername" placeholder="Username" required><br>

            <div class="password-wrapper">
                <input type="password" name="txtpassword" id="txtpassword" placeholder="Password" required>
                <span id="togglePassword">👁️</span>
            </div>

            <select name="cmbtype" id="cmbtype" required>
                <option value="">-- Select account type --</option>
                <option value="ADMINISTRATOR">Administrator</option>
                <option value="TECHNICAL">Technical</option>
                <option value="USER">User</option>
            </select> <br>

            <input type="submit" name="btnsubmit" value="Submit">
            <br>
            <a href="accounts-management.php">Cancel</a>
        </form>
    </div>

    <script>
    // JS para sa show/hide password
    document.addEventListener("DOMContentLoaded", function () {
        const passwordInput = document.getElementById("txtpassword");
        const togglePassword = document.getElementById("togglePassword");

        togglePassword.addEventListener("click", function () {
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);
            this.textContent = type === "password" ? "👁️" : "🙈";
        });
    });
    </script>
</body>
</html>