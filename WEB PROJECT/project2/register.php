<?php
	require('db.php');
	$conn = connection('fooddb');
	
	if (isset($_POST['register'])) {

		$fname = $_POST['fname'];
		$sname = $_POST['sname'];
		$user = $_POST['user'];
		$pass1 = $_POST['pass'];
		$pass2 = $_POST['pass2'];
		$type = $_POST['usertype'];
		

		if ($fname == NULL || $sname == NULL || $user == NULL || $pass1 == NULL || $pass2 == NULL || $type == NULL) {
			echo "Fill in all fields bruh!";
		}else if($pass1 != $pass2){
			echo "Passwords do not match!";
		}else{
			$enc_pass = sha1($pass1);
			$sql = "INSERT INTO users(first_name, second_name, username, password, user_type) VALUES('$fname', '$sname', '$user', '$enc_pass', '$type')";
			setdata($sql, "register");
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="container">
	<p><a href="index.php">Login</a> &nbsp; <a href="register.php">Register</a></p>
	<h3>Register</h3>
	<form action="register.php" method="post">
		<p><label for="fname">First Name</label>
		<input type="text" name="fname" id="fname">
		<p><label for="sname">Second Name</label>
		<input type="text" name="sname" id="sname">
		<p><label for="user">Username</label>
		<input type="text" name="user" id="user">
		<p><label for="pass">Password</label>
		<input type="password" name="pass" id="pass">
		<p><label for="pass2">Confirm Password</label>
		<input type="password" name="pass2" id="pass2">
		<p><label for="usertype">User Type</label>
		<select id="usertype" name="usertype">
			<option value="client">Client</option>
			<option value="admin">Admin</option>
		</select>
		<p><input type="submit" name="register" value="Register">
	</form>
	</div>
</body>
</html>