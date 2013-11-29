<html>
<head>
        <title>DBWEB - Assignment 1</title>        
</head>
<body>
<?php
if(isset($_POST['submitq1'])){
        if(isset($_POST['question1'])){
                if($_POST['question1'] == 2)
                        echo 'You answered the question correctly! <br />';
                else
                        echo 'Wrong answer! Please try again! <br />';
        } else
                echo 'Nothing selected! <br />';        
} else {
?>
        <form action="index.php" method="post">
                Which name starts with an 'a'? <br />
                <input type="radio" name="question1" value="1" />Bob<br />
                <input type="radio" name="question1" value="2" />Alice<br />
                <input type="radio" name="question1" value="3" />John<br />
                <input type="radio" name="question1" value="4" />Dean<br />
				<input type="radio" name="question1" value="5" />Jeanne<br />
                <input type="submit" name="submitq1" value="Submit" />
        </form>
<?php
}
?>
</body>
</html>