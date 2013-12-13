<?php
session_start();

include_once("db-config.php");
try {
    $db = new PDO("mysql:host=localhost;dbname=dbweb",$username,$password); 
}	
    catch (PDOException $e){
    echo 'Connection failed: ' . $e->getMessage();
}

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 604800)) {
    session_unset();     
    session_destroy(); 
}
?>

<html>
<head>
    <title>DBWEB - Assignment 3</title>
</head>
<body>
<?php
	
if(!isset($_POST['submit']) && !isset($_SESSION['currentQ'])) {
    $_SESSION['currentQ'] = 1;
}
if(!isset($_SESSION['correctA'])) {
	$_SESSION['correctA'] = 0;
}
	
$num_q = 'SELECT q_number FROM question';
$res_num_q = $db->query($num_q);

if(!$res_num_q) {
	echo 'Couldn\'t run query ' . mysql_error();
	exit();
}

if($res_num_q->rowCount() == 0) {
	echo 'Couldn\'t find any questions ' . mysql_error();
	exit();
}

if(isset($_POST['submit'])){

    if(isset($_POST['answer'])){
		$_SESSION['LAST_ACTIVITY'] = time();			
		$c = 'SELECT correct FROM choice 
			WHERE c_number = '. $_POST['answer'] . ' AND q_number = '. $_SESSION['currentQ'];
		$res_c = $db->query($c);
		
		if (!$res_c) {
			echo 'Couldn\'t run query ' . mysql_error();
			exit();
		}
		
		if ($res_c->rowCount() == 0) {
			echo 'Couldn\'t find the given choice or the questions';
			exit();
		}
		
		$row_w = $res_c->fetch(PDO::FETCH_ASSOC);
		
        if($row_w['correct'])
            echo 'You answered the question correctly! <br /> <br />';
        else
            echo 'Wrong answer! <br /> </br>';
			
		if(!isset($_SESSION['correctA'])) {
			$_SESSION['correctA'] = $row_w['correct'];
		} else {
			$_SESSION['correctA'] = ($row_w['correct']+$_SESSION['correctA']);
		}
        
		   
        if($_SESSION['currentQ'] == $res_num_q->rowCount()){
			echo 'No more questions to answer. You answered ' . $_SESSION['correctA'] . 
				' questions out of ' . $res_num_q->rowCount() . ' questions correctly.';
				session_unset();     
				session_destroy(); 
				exit;
		}
		else
			$_SESSION['currentQ'] = $_SESSION['currentQ'] + 1;        
    } else
        echo 'Nothing selected! <br />';
}
  
if ($_SESSION['currentQ'] >= 0 && $_SESSION['currentQ'] <= $res_num_q->rowCount()){

	$q = 'SELECT q_text FROM question 
		WHERE q_number = ' . $_SESSION['currentQ'];
	$a = 'SELECT c_text, c_number FROM choice
		WHERE q_number = ' . $_SESSION['currentQ'];;
	$res_q = $db->query($q);
	$res_a = $db->query($a);
	
	if (!$res_q || !$res_a) {
		echo 'Couldn\'t run query ' . mysql_error();
		exit();
	}
	
	if ($res_q->rowCount() == 0 || $res_a->rowCount() == 0) {
		echo 'No questions found ' . mysql_error();
		exit();
	}
	
	$row_q = $res_q->fetch(PDO::FETCH_ASSOC);
	?>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
	<input type="hidden" name="questionNumber" value="<?php echo $_SESSION['currentQ']; ?>" />
	<?php echo $row_q['q_text'] .'<br />';
    while ($row_a = $res_a->fetch(PDO::FETCH_ASSOC)) {
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