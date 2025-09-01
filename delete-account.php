<?php
require_once "config.php";
include("session-checker.php");
if(isset($_POST['btnsubmit']))
{
	$sql = "DELETE FROM tblaccounts WHERE username = ?";
	if($stmt = mysqli_prepare($link, $sql))
	{
		mysqli_stmt_bind_param($stmt, "s", trim($_POST['txtusername']));
		if(mysqli_stmt_execute($stmt))
		{	
			$sql = "INSERT INTO tbllogs (datelog, timelog, action, module, performedto, performedby) VALUES (?, ?, ?, ?, ?, ?)";
			if($stmt = mysqli_prepare($link, $sql)) {
				$date = date("d/m/Y");
				$time = date("h:i:sa");
				$action = "Delete";
				$module = "Accounts Management";
				mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $action, $module, trim($_POST['txtusername']), $_SESSION['username']);
				if(mysqli_stmt_execute($stmt)) {
					echo "User account deleted!";
					header("location: accounts-management.php");
					exit();	
				}
				else {
					echo "<font color = 'red'>Error on insert log statement</font>";
				}
			}
		}
		else{
			echo "<font color = 'red'>Error on delete statement</font>";
		}
	}
}
?>
<html>
<title>Delete Account - Equipment Management System</title>
<body>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
		<input type = "hidden" name = "txtusername" value ="<?php echo trim($_GET['username']); ?>" />
		<p>Are you sure you want to delete this account? </p><br>
		<input type = "submit" name = "btnsubmit" value = "Yes">
		<a href = "accounts-management.php">No</a>
	</form>
</body>
</html>


				