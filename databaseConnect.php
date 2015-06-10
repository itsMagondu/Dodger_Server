<?php

$hostname = "localhost";
$db_user = "root";
$db_password="";
$database="dodger"; 

$db = mysql_connect($hostname,$db_user,$db_password);
mysql_select_db($database,$db);

?>