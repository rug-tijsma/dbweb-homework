<?php
include('config.php');
include('salt.php');

try {
    $db = new PDO("mysql:host=".$hostname.";dbname=".$dbname,$username,$password); 
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

?>
<!DOCTYPE html>
<html>
	<title>
		Database-driven Webtechnology Homework 4
	</title>
<body>
<?php
	if(isset($_POST['submit'])) {
		$error_msg = '';
		if($_POST['username'] && $_POST['password'] && $_POST['confirmpassword']) {
			if($_POST['password'] == $_POST['confirmpassword'] && strlen($_POST['password']) >= 8) {
				$username = strtolower(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
				$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
				
				$prep_stmt = "SELECT userID FROM users WHERE username = ? LIMIT 1";
				$stmt = $db->prepare($prep_stmt);
			 
				if ($stmt) {
					$stmt->bindParam(1, $username);
					$stmt->execute();
					
					if ($stmt->rowCount() == 1) {
						$error_msg .= 'A user with this username already exists.<br />';
					}
				}
				
				if (empty($error_msg)) {
					// Create a random salt
					$random_salt = generateSalt();
			 
					// Create salted password 
					$password = hash('sha512', $password . $random_salt);
			 
					// Insert the new user into the database 
					if ($insert_stmt = $db->prepare("INSERT INTO users (
							username, password, salt) VALUES (?, ?, ?)")) {
			 
						$insert_stmt->bindParam(1,$username);
						$insert_stmt->bindParam(2,$password);
						$insert_stmt->bindParam(3,$random_salt);
					
						$insert_stmt->execute();						
						header("Location: index.php");
					} else {
						$error_msg = "Try again. <br />\n";				
					}
				}
			} else {
				$error_msg = "Password doesn't match the confirmation!<br />\n";
			}
		} else {
			$error_msg = "Fill in all fields!<br />\n";
		}
	}
?>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
	<fieldset style="width:600px">
	<?php
	if (!empty($error_msg)) {
		echo '<p style="color:red;">'.$error_msg.'</p>';
	}
	?>
	<i>Please give a username and password. Use at least 8 characters for your password. </i>
	<table>
		<col width="150">
		<tr>
			<td><label for="username">Username: </label></td>
			<td><input type="text" name="username" maxlength="50" />
		</tr>
		<tr>
			<td><label for="password">Password: </label></td>
			<td><input type="password" name="password" maxlength="50" />
		</tr>
		<tr>
			<td><label for="password">Confirm your password: </label></td>
			<td><input type="password" name="confirmpassword" maxlength="50" />
		</tr>
		<tr>
			<td align="center" colspan="2">
				<input type="submit" name="submit" value="Register"/>
			</td>
		</tr>
		</table>
	</fieldset>
</form>

</body>
</html>