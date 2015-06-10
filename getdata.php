<?php
$hostname = "localhost";
$db_user = "root";
$db_password="";
$database="dodger"; 


$db = mysql_connect($hostname,$db_user,$db_password); 
mysql_select_db($database,$db);

$sql=mysql_query("INSERT INTO users(Name,Email_Adress,username,password) VALUES ('$name','$email','$username','$password')");
if ($sql)
echo "success".mysql_error();

else
echo "An error occured".mysql_error();

?>