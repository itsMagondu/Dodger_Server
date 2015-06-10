<?php

$connection = pg_pconnect("dbname=Dodger user=postgres password = a host=localhost");
if (!$connection) {
    print("Connection Failed.");
    exit;
}
else{
$status  = "unchecked";
$query = "insert into Location (UserID,Longitude,Latitude, status)values('".$_REQUEST['u_id']."','".$_REQUEST['lon']."','".$_REQUEST['lat']."','Unchecked')";
$myquery = pg_exec($connection,$query);
//if (!$query)

}
?>