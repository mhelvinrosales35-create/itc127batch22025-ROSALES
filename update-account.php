<?php
require_once "config.php";
include ("session-checker.php");

if(isset($_POST['btnsubmit'])) { //update account
	$sql = "UPDATE tblaccounts SET password = ?, usertype = ?, status = ? WHERE username = ?";
	if($stmt = mysqli_prepare($link, $sql)) {
		mysqli_stmt_bind_param($stmt, "ssss", $_POST['txtpassword'], $_POST['cmbtype'], $_POST['rbstatus'], $_GET['username']);
		if(mysqli_stmt_execute($stmt)) {
			$sql = "INSERT INTO tbllogs (datelog, timelog, action, module, performedto, performedby) VALUES (?, ?, ?, ?, ?, ?)";
			if($stmt = mysqli_prepare($link, $sql)) {
				$date = date("d/m/Y");
				$time = date("h:i:sa");
				$action = "Update";
				$module = "Accounts Management";
				mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $action, $module, $_GET['username'], $_SESSION['username']);
				if(mysqli_stmt_execute($stmt)) {
					echo "User account updated!";
					header("location: accounts-management.php");
					exit();	
				} else {
					echo "<font color = 'red'>Error on insert log statement</font>";
				}
			}
		} else {
			echo "<font color = 'red'>Error on update statement. </font>";
		}
	}
}
else { //loading the data to the form
	if(isset($_GET['username']) && !empty(trim($_GET['username']))) {
		$sql = "SELECT * FROM tblaccounts WHERE username = ?";
		if($stmt = mysqli_prepare($link, $sql)) {
			mysqli_stmt_bind_param($stmt, "s", $_GET['username']);
			if(mysqli_stmt_execute($stmt)) {
				$result = mysqli_stmt_get_result($stmt);
				$account = mysqli_fetch_array($result, MYSQLI_ASSOC);
			} else {
				echo "<font color = 'red'>Error on loading account data.</font>";
			}
		}
	}
}
?>
<html>
<head>
	<title>Update Account - Equipment Management</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			background: url('updateaccountbg.jpg') no-repeat center center fixed;
			background-size: cover;
			margin: 0;
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100vh;
		}
		.container {
			background: rgba(255, 255, 255, 0.9);
			padding: 30px;
			border-radius: 12px;
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
			width: 400px;
			text-align: center;
		}
		input[type="text"], input[type="password"], select {
			width: 100%;
			padding: 8px;
			margin: 8px 0;
			border: 1px solid #ccc;
			border-radius: 6px;
		}
		.password-wrapper {
			position: relative;
			width: 100%;
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
		.radio-group {
			text-align: left;
			margin: 10px 0;
		}
		input[type="submit"], a {
			display: inline-block;
			margin-top: 15px;
			padding: 8px 15px;
			border: none;
			border-radius: 6px;
			cursor: pointer;
			text-decoration: none;
		}
		input[type="submit"] {
			background: #28a745;
			color: white;
		}
		a {
			background: #dc3545;
			color: white;
		}
	</style>
</head>
<body>
	<div class="container">
		<h2>Update Account</h2>
		<p>Change the values and submit to update the account.</p>
		<form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="POST">
			<p><b>Username:</b> <?php echo $account['username']; ?></p>

			<label>Password:</label>
			<div class="password-wrapper">
				<input type="password" id="txtpassword" name="txtpassword" value="<?php echo $account['password']; ?>" required>
				<span id="togglePassword">👁️</span>
			</div>

			<label>Current User type: </label>
			<p><?php echo $account['usertype']; ?></p>

			<label>Change User type:</label>
			<select name="cmbtype" id="cmbtype" required>
				<option value="">--Select Account Type--</option>
				<option value="ADMINISTRATOR">Administrator</option>
				<option value="TECHNICAL">Technician</option>
				<option value="USER">User</option>
			</select><br>

			<div class="radio-group">
				<label>Status:</label><br>
				<?php
					$status = $account['status'];
					if($status == 'ACTIVE') {
						echo '<input type="radio" name="rbstatus" value="ACTIVE" checked> Active<br>';
						echo '<input type="radio" name="rbstatus" value="INACTIVE"> Inactive<br>';
					} else {
						echo '<input type="radio" name="rbstatus" value="ACTIVE"> Active<br>';
						echo '<input type="radio" name="rbstatus" value="INACTIVE" checked> Inactive<br>';
					}
				?>
			</div>

			<input type="submit" name="btnsubmit" value="Update">
			<a href="accounts-management.php">Cancel</a>
		</form>
	</div>

	<script>
		document.getElementById("togglePassword").addEventListener("click", function() {
			const passwordField = document.getElementById("txtpassword");
			if (passwordField.type === "password") {
				passwordField.type = "text";
				this.textContent = "🙈";
			} else {
				passwordField.type = "password";
				this.textContent = "👁️";
			}
		});
	</script>
</body>
</html>