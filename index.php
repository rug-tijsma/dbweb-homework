<?php
session_start();

include('salt.php');

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 604800)) {
    session_unset();     
    session_destroy(); 
}

if (isset($_GET['logout'])) {
	session_destroy();
	header("Location: index.php");
	exit;
}

if (isset($_SESSION['username'])) {
	header("Location: mainpage.php");
	exit;
}

include('config.php');
try {
    $db = new PDO("mysql:host=".$hostname.";dbname=".$dbname,$username,$password); 
} catch (PDOException $e){
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

if (isset($_POST['submit'])) {
	if (!isset($_POST['username']) || !isset($_POST['password'])) {
		echo 'Please fill in all fields!';
	} else {
		$query = "SELECT password, salt, username, userID 
				FROM users 
				WHERE username=".$db->quote($_POST['username']); 
		$result = $db->query($query);

		if (!empty($result) && $result->rowCount() > 0) {		
			$row = $result->fetch(PDO::FETCH_ASSOC);
			$hash = hash('sha512',$_POST['password'] . $row['salt']);
			
			if ($hash == $row['password']) {
				$_SESSION['username'] = $row['username'];
				$_SESSION['userID'] = $row['userID'];
				header("Location: mainpage.php");
			} else {
				echo 'Invalid username or password!</br>';
			}
		} else {
			echo 'Invalid username or password!</br>';
		}
	}
}
?>
<b>Login</b></br>
<form action="index.php" method="post">
Username: <input type="text" name="username" /><br />
Password: <input type="password" name="password" /><br />
<input type="submit" name="submit" value="Log in" />
</form>

<a href="register.php">Register here</a></br>
</body>
</html>