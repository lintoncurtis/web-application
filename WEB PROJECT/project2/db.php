<?php
session_start();

function connection($dbname){
	$conn = new mysqli('localhost', 'root', '', 'fooddb');
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connection_error);
	}
	return $conn;
}

function setdata($sql, $place){
	$conn = connection('fooddb');

	if ($place == 'food') {
		if($conn->query($sql) == TRUE) {
		//echo "Operation was successful";
		header('Location: menu.php');
		} else{
			echo "Error: ".$sql."<br>".$conn->error;
		}
	}else if($place == 'register'){
		if($conn->query($sql) == TRUE) {
		//echo "Operation was successful";
			header('Location: index.php');
		} else{
			echo "Error: ".$sql."<br>".$conn->error;
		}
	}else{
		if($conn->query($sql) == TRUE) {
			//echo "Order Confirmed";
		} else{
			echo "Error: ".$sql."<br>".$conn->error;
		}
	}
	$conn->close();
}

function getdata($sql, $place){
	$conn = connection('fooddb');
	$result = $conn->query($sql);

	if ($place == 'select') {
		if ($result->num_rows > 0) {
			while ($foods = $result->fetch_assoc()){
				echo "
				<option value=". $foods['food_item'].">".$foods['food_item']."</option>
				";
			}
		} else{
			echo "No results";
		}
	}else if($place == 'table'){
		$i = 1;
		
		if ($result->num_rows > 0) {
			$pages = ceil($result->num_rows/5);
			for ($page=1; $page <= $pages; $page++) { 
				echo "<tr style='height: 20px;'>";
					for ($j=0; $j < 5; $j++) { 

						if ($row = $result->fetch_assoc()){	

							if(array_key_exists('food_item', $row)){
								$img = 'data:image/jpeg;base64,'. base64_encode($row['food_image']);
								echo "
								<tr>
								<td>".$i."</td>
								<td>".$row['food_item']."</td>
								<td><img src='$img'></td>
								<td>".$row['price']."</td>
								<td>
									";
		
								$username = $_SESSION['username'];
								$sql = "SELECT * FROM users WHERE username = '$username'";
								$user = getdata($sql, null);

								if ($user['user_type'] == "admin") {
									echo "<form method='post' action='edit.php'>";
								} else if ($user['user_type'] == "client") {
									echo "<form method='post' action='main.php'>";
								}
								
								echo "
									<input type='hidden' name='id' value='".$row['food_id']."'>
									<input type='hidden' name='food_item' value='".$row['food_item']."'>
									<input type='hidden' name='price' value='".$row['price']."'>";


									if ($user['user_type'] == "admin") {
										echo "<button type='submit'>Edit</button>";
									} else if ($user['user_type'] == "client") {
										echo "<button type='submit'>Buy</button>";
									}
										echo"
									</form>
								</td>
								</tr>
								";
								$i++;
							} else if(array_key_exists('username', $row)){
								echo "
								<tr>
								<td>".$i."</td>
								<td>".$row['first_name']."</td>
								<td>".$row['second_name']."</td>
								<td>".$row['username']."</td>
								<td>".$row['user_type']."</td>
								</tr>
									";
		
								/*$username = $_SESSION['username'];
								$sql = "SELECT * FROM users WHERE username = '$username'";
								$user = getdata($sql, null);

								if ($user['user_type'] == "admin") {
									echo "<form method='post' action='edit.php'>";
								} else if ($user['user_type'] == "client") {
									echo "<form method='post' action='main.php'>";
								}
								
								echo "
									<input type='hidden' name='id' value='".$row['food_id']."'>
									<input type='hidden' name='food_item' value='".$row['food_item']."'>
									<input type='hidden' name='price' value='".$row['price']."'>";


									if ($user['user_type'] == "admin") {
										echo "<button type='submit'>Edit</button>";
									} else if ($user['user_type'] == "client") {
										echo "<button type='submit'>Buy</button>";
									}
										echo"
									</form>
								</td>
								</tr>
								";*/
								$i++;
							}
					}
				echo "</tr>";

				}
			}		
		} else{
			echo "No results";
		}
	}else if($place == 'search') {
		$i = 1;
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()){
				$img = 'data:image/jpeg;base64,'. base64_encode($row['food_image']);
				echo "
				<tr>
				<td>".$i."</td>
				<td>".$row['food_item']."</td>
				<td><img src='$img'></td>
				<td>".$row['price']."</td>
				<td>
					<form method='post' action='edit.php'>
					<input type='hidden' name='id' value='".$row['food_id']."'>
						<input type='hidden' name='food_item' value='".$row['food_item']."'>
						<input type='hidden' name='price' value='".$row['price']."'>
						<button type='submit'>Edit</button>
					</form>
				</td>
				</tr>
				";
				$i++;
			}
		} else{
			echo "No results";
		}
	}else if ($place == 'login') {
		if ($result->num_rows > 0) {
			while ($user = $result->fetch_assoc()){
				return $user['password'];	
			}
		} else{
			echo "Username does not exist!";
		}
	}else{
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()){
				return $row;	
			}
		}
	}
	$conn->close();
}

function logout(){
	session_destroy();
}

















?>