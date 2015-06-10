<?php

$connection = pg_pconnect("dbname=Dodger user=postgres password = a host=localhost");
if (!$connection) {
    print("Connection Failed.");
    exit;
}
else
    {
$sql = pg_query("insert into events (typeID,Longitude,Latitude,Description,userID)values('".$_REQUEST['typeid']."','".$_REQUEST['lon']."',
'".$_REQUEST['lat']."','".$_REQUEST['Description']."','".$_REQUEST['userID']."')");

$r=pg_query($sql);
if(!$r)
echo "Error in query: ".mysql_error();


}
?>