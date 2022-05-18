<?php
	
	require('db.php');
	$conn = connection('fooddb');

	if (isset($_POST['submit'])) {
		$food = $_POST['food'];
		$image = addslashes(file_get_contents($_FILES["imageUpload"]["name"]));
		$price = $_POST['price'];

		$check = getimagesize($_FILES["imageUpload"]["name"]);
		$imageType = $check["mime"];

		//$filetype = substr($_FILES["imageUpload"]["name"], strpos($_FILES["imageUpload"]["name"], '.'), strlen($_FILES["imageUpload"]["name"]));

	    if($check !== false) {
	        $imageType = $check["mime"];
	        $sql = "INSERT INTO foods(food_item, food_image, price) VALUES('$food','$image', '$price')";
			setdata($sql, "food");


	    } else {
	        echo "File is not an image.";
	    }
	}
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>Food</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="container">
	<form style="text-align: right;" action="index.php" method="post">
		<p style="text-align: right;"><?php if(isset($_SESSION['username'])){echo $_SESSION['username']." is logged in.";}?>
		<input type="submit" name="logout" value="Logout"></p>
	</form>
	<p><a href="main.php">Main</a> &nbsp; <a href="upload.php">Upload</a> &nbsp; <a href="menu.php">Menu</a></p>
	<h3>Upload</h3>
	<form action="upload.php" method="post" enctype="multipart/form-data">
		<p><label for="foods">Food Item</label>
		<input type="text" name="food" id="foods">
		<p><label for="image">Upload image</label>
		<input type="file" name="imageUpload" id="image">
		<p><label for="price">Price</label>
		<input type="number" name="price" id="price">
		<p><input type="submit" name="submit" value="Save">
	</form>
	</div>



</body>
</html>