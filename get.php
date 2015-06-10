<?php

$connection = pg_pconnect("dbname=Dodger user=postgres password = a host=localhost");
if (!$connection) {
    print("Connection Failed.");
    exit;
}
else{

$query = "Select userid, Name, username ,password from users";
$myquery = pg_exec($connection,$query);

while($row = pg_fetch_assoc($myquery))
$output[]=$row;
print(json_encode($output));

}
?>