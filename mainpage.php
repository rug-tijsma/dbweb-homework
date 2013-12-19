<?php
session_start();

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
<table width=100%>
    <tr>
        <td style='background-color:#FFFFFF;'>
			<?php include('quiz.php');?>
        </td>
        <td style='background-color:#FFFFFF;'>
            Your top scores</br>
			<?php
			$query = 'SELECT num_cor, date
					FROM scores
					WHERE username = "'.$_SESSION['username'].'"
					ORDER BY num_cor DESC, date DESC
					LIMIT 5';
			$result = $db->query($query);
			
			if (!$result) {
				echo 'Couldn\'t run query ' . mysql_error();
				exit();
			}
	
			if ($result->rowCount() == 0) {
				echo 'No previous results found ';
			} else {
				while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
					echo 'Correct: '.$row['num_cor'].' Date: '.$row['date'].'</br>';
				}
			}
			?>
			
        </td>
        <td style='background-color:#FFFFFF;'>
            Overall top scores</br>
			
			<?php
			$query = 'SELECT username, num_cor, date
					FROM scores
					ORDER BY num_cor DESC, date DESC
					LIMIT 5';
			$result = $db->query($query);
			
			if (!$result) {
				echo 'Couldn\'t run query ' . mysql_error();
				exit();
			}
	
			if ($result->rowCount() == 0) {
				echo 'No previous results found';
			} else {
				while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
					echo 'User: '.$row['username'].' Correct: '.$row['num_cor'].' Date: '.$row['date'].'</br>';
				}
			}
			?>
        </td>
    </tr>
</table>

<a href="index.php?logout">Log out</a></br>
</body>
</html>