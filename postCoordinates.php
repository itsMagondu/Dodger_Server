<?php

$hostname = "localhost";
$db_user = "root";
$db_password="";
$database="dodger"; 

$db = mysql_connect($hostname,$db_user,$db_password); 
mysql_select_db($database,$db);
$sql=mysql_query("insert into Location (UserID,Longitude,Latitude)values('".$_REQUEST['u_id']."','".$_REQUEST['lon']."','".$_REQUEST['lat']."')");
//for updation
//$sql=update CITY set CITY_NAME='".$_REQUEST['c_name']."' where CITY_ID=22
$r=mysql_query($sql);
if(!$r)
echo "Error in query: ".mysql_error();
mysql_close();
?>