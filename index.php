<html>
<head>
    <title>DBWEB - Assignment 1</title>
</head>
<body>
<?php
if(!isset($_POST['submit']) && !isset($_POST['nextQuestion']))
    $questionNumber = 0;
else
    $questionNumber = $_POST['questionNumber'];
  
    $arrayQ = array("The color of an orange is:", "The color of a banana is:", "The color of a pear is:");
    $arrayA = array("Orange", "Yellow", "Green");
  
if(isset($_POST['submit'])){
    if(isset($_POST['answer'])){
        if($_POST['answer'] == $questionNumber + 1)
            echo 'You answered the question correctly! <br />';
        else
            echo 'Wrong answer!';
              
        $questionNumber++;
          
        if($questionNumber < sizeof($arrayQ)){
		?>
<form action="index.php" method="post">
<input type="submit" name="nextQuestion" value="Next" />
<input type="hidden" name="questionNumber" value="<?php echo $questionNumber; ?>" />
</form>
<?php
        }
    } else
        echo 'Nothing selected! <br />';
}
  
if (($questionNumber == 0 || isset($_POST['nextQuestion'])) && $questionNumber <= sizeof($arrayQ)){
  
?>
<form action="index.php" method="post">
<?php echo $arrayQ[$questionNumber];?> <br />
<input type="hidden" name="questionNumber" value="<?php echo $questionNumber; ?>" />
<?php
        foreach ($arrayA as $i => $value) {
            echo '<input type="radio" name="answer" value="'. ($i+1).'" />'. $value .' <br />';
        }
        ?>
<input type="submit" name="submit" value="Submit" />
</form>
<?php
}
?>
</body>
</html>