<?php include 'databaselogin.php';?>

<html>
<head>
    <title>DBWEB - Assignment 2</title>
</head>
<body>
<?php

if(!isset($_POST['submit']) && !isset($_POST['nextQuestion'])) {
    $questionNumber = 1;
} else {
	if(!isset($_COOKIE['Koekje_q'])) {
		$questionNumber = 1;
	} else {
		$questionNumber = $_COOKIE['Koekje_q'];
	}
}
	
$num_q = 'SELECT q_number FROM question';
$res_num_q = mysql_query($num_q);

if(!$res_num_q) {
	echo 'Couldn\'t run query ' . mysql_error();
	exit();
}

if( mysql_num_rows($res_num_q) == 0) {
	echo 'Couldn\'t find any questions ' . mysql_error();
	exit();
}

if(isset($_POST['submit'])){
    if(isset($_POST['answer'])){
		$c = 'SELECT correct FROM choice 
			WHERE c_number = '. $_POST['answer'] . ' AND q_number = '. $questionNumber;
		$res_c = mysql_query($c);
		
		if (!$res_c) {
			echo 'Couldn\'t run query ' . mysql_error();
			exit();
		}
		
		if (mysql_num_rows($res_c) == 0) {
			echo 'Couldn\'t find the given choice or the questions';
			exit();
		}
		
		$row_c = mysql_fetch_assoc($res_c);
		
        if($row_c['correct'])
            echo 'You answered the question correctly! <br />';
        else
            echo 'Wrong answer! <br />';
			
		if(!isset($_COOKIE['Koekje_a'])) {
			setCookie('Koekje_a', $row_c['correct'], time()+3600);
		} else {
			setCookie('Koekje_a', ($row_c['correct'] + $_COOKIE['Koekje_a']), time()+3600);
		}
        
		$questionNumber++;
          
        if($questionNumber < mysql_num_rows($res_num_q) + 1){
		?>
			<form action="index.php" method="post">
			<input type="submit" name="nextQuestion" value="Next" />
			<input type="hidden" name="questionNumber" value="<?php echo $questionNumber; ?>" />
			</form>
		<?php
			setCookie('Koekje_q', $questionNumber, time()+3600);
        } else {
			//Last question. Cookie isn't sent yet so need to update the count of correct answers.	
			echo 'No more questions to answer. You answered ' . ($_COOKIE['Koekje_a'] + $row_c['correct']) . 
				' questions out of ' . mysql_num_rows($res_num_q) . ' questions correctly.';
		}
    } else
        echo 'Nothing selected! <br />';
}
  
if (($questionNumber == 1 || isset($_POST['nextQuestion'])) && $questionNumber <= mysql_num_rows($res_num_q)){

	$q = 'SELECT q_text FROM question 
		WHERE q_number = ' . $questionNumber;
	$a = 'SELECT c_text, c_number FROM choice
		WHERE q_number = ' . $questionNumber;
	$res_q = mysql_query($q);
	$res_a = mysql_query($a);
	
	if (!$res_q || !$res_a) {
		echo 'Couldn\'t run query ' . mysql_error();
		exit();
	}
	
	if (mysql_num_rows($res_q) == 0 || mysql_num_rows($res_a) == 0) {
		echo 'No questions found ' . mysql_error();
		exit();
	}
	
	$row_q = mysql_fetch_assoc($res_q);
  
	?>
	<form action="index.php" method="post">
	<input type="hidden" name="questionNumber" value="<?php echo $questionNumber; ?>" />
	<?php echo $row_q['q_text'] .'<br />';
    while ($row_a = mysql_fetch_assoc($res_a)) {
		echo '<input type="radio" name="answer" value="'. $row_a['c_number'] .'" />'. $row_a['c_text']. ' <br />';
	}
    ?>
	<input type="submit" name="submit" value="Submit" />
</form>
<?php
}
?>
</body>
</html>