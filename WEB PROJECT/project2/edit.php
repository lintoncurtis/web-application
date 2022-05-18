<?php	
	require('db.php');
	$conn = connection('fooddb');

	if (isset($_POST['edit'])) {
		echo "<pre>";
		print_r($_POST);
		print_r($_FILES);
		echo "</pre>";

		$id = $_POST['id'];
		$food = $_POST['food'];
		$price = $_POST['price'];

		if ($_FILES["imageUpload"]["size"] <> 0) {
			$image = addslashes(file_get_contents($_FILES["imageUpload"]["name"]));
			$check = getimagesize($_FILES["imageUpload"]["name"]);
			
			if($check !== false) {
		        //$imageType = $check["mime"];
		        $sql = "UPDATE foods SET food_item = '$food', food_image = '$image', price = '$price' WHERE food_id='$id'";
				setdata($sql, "food");
		    } else {
		        echo "File is not an image.";
		    }
		}else{
			$sql = "UPDATE foods SET food_item = '$food', price = '$price' WHERE food_id='$id'";
			setdata($sql, "food");
		}
	}else if (isset($_POST['delete'])) {
		echo "<pre>";
		print_r($_POST);
		print_r($_FILES);
		echo "</pre>";

		$id = $_POST['id'];

		$sql = "DELETE FROM foods WHERE food_id='$id'";
		setdata($sql, "food");
		
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
	<h3>Edit</h3>
	<form action="edit.php" method="post" enctype="multipart/form-data">
		<input type="hidden" name="id" value="<?php echo $_POST['id']?>">
		<p><label for="foods">Food Item</label>
		<input type="text" name="food" id="foods" value="<?php if(isset($_POST['food_item'])){echo $_POST['food_item'];}?>">
		<p><label for="image">Upload image</label>
		<input type="file" name="imageUpload" id="image">
		<p><label for="price">Price</label>
		<input type="number" name="price" id="price" value="<?php if(isset($_POST['price'])){echo $_POST['price'];}?>">
		<p><input type="submit" name="edit" value="Save"> &nbsp; <input type="submit" name="delete" value="Delete">
	</form>
	</div>



</body>
</html>