<?php

if (!mysql_connect('hostname here', 'username here', 'password here')) {
	echo 'Connection to database failed.';
	exit();
} elseif (!mysql_select_db('dbweb')){
	echo 'Couldn\'t select database dbweb';
	exit();
}
?>