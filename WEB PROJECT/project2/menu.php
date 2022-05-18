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
	<title>Menu</title>
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
					echo "<p><a href='main.php'>Main</a> &nbsp; <a href='menu.php'>Menu</a></p>";
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
				<th>Item</th>
				<th>Image</th>
				<th>Price</th>
				<?php 

					//user ordering form
					if ($user['user_type'] == "admin") {
						echo "<th>Edit</th>";
					}else {
						echo "<th>Buy</th>";
					}
				}
				?>
			</tr>
			</thead>
			<?php
			$conn = connection('fooddb');

			if (isset($_GET['submit'])) {
				$food = $_GET['query'];
				$sql = "SELECT * FROM foods WHERE food_item LIKE '%$food%'";
				$result = $conn->query($sql);
				getdata($sql, 'search');
			}else{
				$sql = "SELECT * FROM foods";
				$result = $conn->query($sql);
				getdata($sql, 'table');
			}

			?>
		</table>
	</div>
</body>
</html>