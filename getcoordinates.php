<?php

$hostname = "localhost";
$db_user = "root";
$db_password="";
$database="dodger"; 

$db = mysql_connect($hostname,$db_user,$db_password); 
mysql_select_db($database);

$sql=mysql_query("Select Longitude, Latitude, Time from location");
while($row = mysql_fetch_assoc($sql))
$output[]=$row;
print(json_encode($output));
mysql_close();
?>