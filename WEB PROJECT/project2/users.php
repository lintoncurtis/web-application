<?php
	require('db.php');
	$conn = connection('fooddb');
	
	if(isset($_SESSION['username'])){
			$username = $_SESSION['username'];
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>All users</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="container" class="menu">
		<form style="text-align: right;" action="index.php" method="post">
			<p style="text-align: right;"><?php if(isset($_SESSION['username'])){echo $_SESSION['username']." is logged in.";}?>
			<input type="submit" name="logout" value="Logout"></p>
		</form>
		<?php
			if(isset($_SESSION['username'])){
				$username = $_SESSION['username'];
				$sql = "SELECT * FROM users WHERE username = '$username'";
				$user = getdata($sql, null);

				if ($user['user_type'] == "admin") {
					echo "<p><a href='main.php'>Main</a> &nbsp; <a href='upload.php'>Upload</a> &nbsp; <a href='menu.php'>Menu</a></p>";
				}else {
					logout();
				}
			}

		?>
		<h3>Menu</h3>
		<form id="search" method="get" action="menu.php">
			<p><input type="text" name="query" id="searchbox"> <input type="submit" name="submit" value="Search"></p>
		</form>
		<table id="menu">
			<thead>
			<tr>
				<th>#</th>
				<th>First Name</th>
				<th>Second Name</th>
				<th>Username</th>
				<th>User Type</th>
			</tr>
			</thead>
			<?php
			$conn = connection('fooddb');

				if (isset($_GET['submit'])) {
					$query = $_GET['query'];
					$sql = "SELECT * FROM users WHERE first_name LIKE '%$query%' OR second_name LIKE '%$query% OR username LIKE '%$query%''";
					$result = $conn->query($sql);
					getdata($sql, 'search');
				}else{
					$sql = "SELECT * FROM users";
					$result = $conn->query($sql);
					getdata($sql, 'table');
				}

			?>
		</table>
	</div>
</body>
</html>