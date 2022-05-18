<?php
	require('db.php');
	$conn = connection('fooddb');

	if(isset($_SESSION['username'])){
			$username = $_SESSION['username'];
	}	

	if (isset($_POST['pay'])) {
		$sql = "SELECT * FROM users WHERE username = '$username'";
		$user = getdata($sql, null);
		$user_id = $user['id'];

		$sql = "SELECT * FROM orders WHERE user_id = '$user_id' AND status = 'Pending' ORDER BY id DESC";
		$result = $conn->query($sql);
		$order = $result->fetch_assoc();
		$order_id = $order['id'];

		$sql = "SELECT * FROM order_details WHERE order_id = '$order_id' AND status = 'Pending'";
		$result = $conn->query($sql);	

		while($order_detail = $result->fetch_assoc()){
			$sql = "UPDATE order_details SET status = 0 WHERE order_id = '$order_id'";
			setdata($sql, null);
		}

		$sql = "UPDATE orders SET status = 0 WHERE id = '$order_id'";
		setdata($sql, null);
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
	<?php
		if(isset($_SESSION['username'])){
			$username = $_SESSION['username'];
			$sql = "SELECT * FROM users WHERE username = '$username'";
			$user = getdata($sql, null);

			//user ordering form
			if ($user['user_type'] == "client"){ 
				//echo "I'm a user";
				?>
				<p><a href="main.php">Main</a> &nbsp; <a href="menu.php">Menu</a></p>
				<h3>Make Order</h3>
				<form action="main.php" method="post">
					<p><label for="foods">Food Item</label>
					<select id="foods" name="food">
						<?php
						$sql = "SELECT * FROM foods";
						getdata($sql, 'select');
						?>
					</select>
					<p><label for="qty">Quantity</label>
					<input type="number" name="qty" id="qty">
					<p><input type="submit" name="add" value="Add Item">
					<p><input type="submit" name="submit" value="Finish Order">
				</form>
				 
			<div id="display">

			<?php
			$user_id = $user['id'];
			$sql = "SELECT * FROM orders WHERE user_id = $user_id AND amount > 0 AND status = 'Pending' ";
			$pendingCheck = $conn->query($sql);

				if($pendingCheck->num_rows > 0){
					
					echo "<p>There's a pending amount waiting to be paid:</p>";

					$sql = "SELECT * FROM users WHERE username = '$username'";
					$user = getdata($sql, null);
					$user_id = $user['id'];

					$sql = "SELECT * FROM orders WHERE user_id = '$user_id' AND status = 'Pending' ORDER BY id DESC";
					$result = $conn->query($sql);
					$order = $result->fetch_assoc();
					$order_id = $order['id'];

					$sql = "SELECT * FROM order_details WHERE status = 'Pending'";
					$result = $conn->query($sql);
					?>

					<ul>
						<?php
							$Total = 0;
							if ($result->num_rows > 0) {
								while($order_detail = $result->fetch_assoc()){
									$amount = ($order_detail['unit_amount'])*($order_detail['quantity']);
									echo "<li>".$order_detail['description']." - ".$amount."</li>";
									$Total += $amount;
								}
							}	
						?>
					</ul>
			<?php
					echo "Total Amount: ".$Total."
							<form style='text-align: right;' action='main.php' method='post'>
							<input type='submit' name='pay' value='Pay Amount'></p>
							</form>";
				}else if (isset($_POST['submit'])) {
					$food = $_POST['food'];
					$qty = $_POST['qty'];
					$sql = "SELECT * FROM foods WHERE food_item = '$food'";
					$result = $conn->query($sql);

					echo "<p>You ordered:</p>";

					if ($result->num_rows > 0) {
						while ($foods = $result->fetch_assoc()){
							$img = 'data:image/jpeg;base64,'. base64_encode($foods['food_image']);
							if (isset($qty) && $qty<>null && $qty<>0 && isset($_POST['food'])) {
								echo "$qty ".$foods['food_item']."s <br>";
								for ($i=0; $i < $qty; $i++) { 
								echo "<img src='$img'>";
								}
								echo "<br>";
							}else{
								echo "Nothing yet. Please select a food item and its quantity";
							}
						}
					}

					$user_id = $user['id'];

					$sql = "SELECT * FROM orders WHERE user_id = '$user_id' AND amount = 0 ORDER BY id DESC";
					$result = $conn->query($sql);
					$order = $result->fetch_assoc();
					$order_id = $order['id'];

					$sql = "SELECT price FROM foods WHERE food_item = '$food'";
					$result = $conn->query($sql);
					$price = $result->fetch_assoc()['price'];

					$sql = "INSERT INTO order_details(order_id, unit_amount, description, quantity, status) VALUES('$order_id','$price','$qty ' '$food','$qty','Pending')";
					setdata($sql, null);
					echo "$qty ".$food." added to order!<br>";

					$sql = "SELECT * FROM order_details WHERE order_id = '$order_id'";
					$result = $conn->query($sql);	
			?>
					<ul>
						<?php
							while($order_detail = $result->fetch_assoc()){
								$amount = ($order_detail['unit_amount'])*($order_detail['quantity']);
								$sql = "UPDATE orders SET amount = (amount+'$amount') WHERE id='$order_id'";
								setdata($sql, null);
								echo "<li>".$order_detail['description']." - ".$amount."</li>";
							}
						?>
					</ul>


					Total amount: 
			<?php
				$sql = "SELECT amount FROM orders WHERE id = '$order_id'";
					$result = $conn->query($sql);
					$order = $result->fetch_assoc();
					$amount = $order['amount'];
					echo $amount."
						<form style='text-align: right;' action='main.php' method='post'>
							<input type='submit' name='pay' value='Pay Amount'></p>
						</form>
					";



				}else if(isset($_POST['add'])){
					$food = $_POST['food'];
					$qty = $_POST['qty'];
					$sql = "SELECT * FROM foods WHERE food_item = '$food'";
					$result = $conn->query($sql);

					echo "<p>You ordered:</p>";

					if ($result->num_rows > 0) {
						while ($foods = $result->fetch_assoc()){
							$img = 'data:image/jpeg;base64,'. base64_encode($foods['food_image']);
							if (isset($qty) && $qty<>null && $qty<>0 && isset($_POST['food'])) {
								echo "$qty ".$foods['food_item']."s <br>";
								for ($i=0; $i < $qty; $i++) { 
								echo "<img src='$img'>";
								}
								echo "<br>";
							}else{
								echo "Nothing yet. Please select a food item and its quantity";
							}
						}
					}

					$user_id = $user['id'];

					$sql = "SELECT * FROM orders WHERE user_id = '$user_id' AND amount = 0 ORDER BY id DESC";
					$result = $conn->query($sql);

					if ($result->num_rows != null) {
						$order = $result->fetch_assoc();
						$result = $conn->query($sql);
						$order_id = $order['id'];

						$sql = "SELECT price FROM foods WHERE food_item = '$food'";
						$result = $conn->query($sql);
						$price = $result->fetch_assoc()['price'];

						$sql = "INSERT INTO order_details(order_id, unit_amount, description, quantity, status) VALUES('$order_id','$price','$qty ' '$food','$qty','Pending')";
						setdata($sql, null);
						echo "$qty ".$food." added to order!<br>";

						$sql = "SELECT * FROM orders WHERE user_id = '$user_id' AND amount = 0 ORDER BY id DESC";
						$result = $conn->query($sql);
						$order = $result->fetch_assoc();
						$order_id = $order['id'];

						$sql = "SELECT * FROM order_details WHERE order_id = '$order_id'";
						$result = $conn->query($sqlm`);	
						?>
							<ul>
								<?php
									$Total = 0;
									while($order_detail = $result->fetch_assoc()){
										$amount = ($order_detail['unit_amount'])*($order_detail['quantity']);
										echo "<li>".$order_detail['description']." - ".$amount."</li>";
										$Total += $amount;
									}

									echo "Total Amount: ".$Total."<br>";
								?>
							</ul>
						<?php

					}else{
						$sql = "INSERT INTO orders(user_id,status) VALUES('$user_id','Pending')";
						setdata($sql, null);

						$sql = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY id DESC";
						$result = $conn->query($sql);
						$order = $result->fetch_assoc();
						$order_id = $order['id'];

						$sql = "SELECT price FROM foods WHERE food_item = '$food'";
						$result = $conn->query($sql);
						$price = $result->fetch_assoc()['price'];

						$sql = "INSERT INTO order_details(order_id, unit_amount, description, quantity, status) VALUES('$order_id','$price','$qty ' '$food','$qty','Pending')";
						setdata($sql, null);

						echo "$qty ".$food." added to order!<br>";


						$sql = "SELECT * FROM orders WHERE user_id = '$user_id' AND amount = 0 ORDER BY id DESC";
						$result = $conn->query($sql);
						$order = $result->fetch_assoc();
						$order_id = $order['id'];

						$sql = "SELECT * FROM order_details WHERE order_id = '$order_id'";
						$result = $conn->query($sql);	
						?>
							<ul>
								<?php
									$Total = 0;
									while($order_detail = $result->fetch_assoc()){
										$amount = ($order_detail['unit_amount'])*($order_detail['quantity']);
										echo "<li>".$order_detail['description']." - ".$amount."</li>";
										$Total += $amount;
									}
									echo "Total Amount: ".$Total."<br>";
								?>
							</ul>
						<?php
					}
				}else {
					echo "Nothing yet. Please select a food item and its quantity";
				}
			} else if($user['user_type'] == "admin"){
				//echo "not a user, i'm an admin";

			?>
				<p><a href="main.php">Main</a> &nbsp; <a href="upload.php">Upload</a> &nbsp; <a href="menu.php">Menu</a></p>
				<ul>
					<li><a href="users.php">View All Users</a></li>
					<li><a href="#">View All Transactions</a></li>
				</ul>
			<?php
			}
		}else{
			header('Location: index.php');
		}
	?>
	</div>

	</div>



</body>
</html>

<script type="text/javascript" src="main.js"></script>