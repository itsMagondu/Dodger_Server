<?php

$connection = pg_pconnect("dbname=Dodger user=postgres password = a host=localhost");
if (!$connection) {
    print("Connection Failed.");
    exit;
}
else
    $existingNodes = "SELECT * FROM location Where status = 'Unchecked'";
$loop1 = pg_exec($connection, $existingNodes);
//echo pg_num_rows($loop1);
for ($lt = 0; $lt < pg_numrows($loop1); $lt++) {
    $locID = pg_result($loop1, $lt, 0);
    $userID = pg_result($loop1, $lt, 1);
    $long = pg_result($loop1, $lt, 2);
    $lat = pg_result($loop1, $lt, 3);
    $time = pg_result($loop1, $lt, 4);
    $status = pg_result($loop1, $lt, 5);

    print("           Loc: $locID \n");
    print("           User: $userID \n");
    print("           Long: $long \n");
    print("           Lat: $lat \n");
    print("           Time: $time \n");
    print("           Stat: $status \n");

    $closestNode = "SELECT itemid, ST_Distance(ST_Point(linearcs.x_cord, linearcs.y_cord),'POINT($lat  $long)'::geometry) FROM
    linearcs WHERE ST_DWithin(ST_Point(linearcs.x_cord, linearcs.y_cord),'POINT($lat  $long)'::geometry, 100) ORDER BY ST_Distance
    (ST_Point(linearcs.x_cord, linearcs.y_cord),'POINT($lat  $long)'::geometry) LIMIT 1";
    // $closestNode = "SELECT itemid FROM linearcs ORDER BY ST_Distance(ST_Point(linearcs.x_cord, linearcs.y_cord),'POINT($lat  $long)'::geometry)LIMIT 1";
    $loop2 = pg_exec($connection, $closestNode);
    //echo pg_num_rows($loop2);
   // pg_fetch_result($result, 0, 0);
   $loc = pg_fetch_result($loop2,0,0);
   echo " Location : ";
    echo $loc;
   $dist= pg_fetch_result($loop2,0,1);
   // $loc = $row['itemID'];
    echo " Distance: ";
    echo $dist;

    $update = "UPDATE location SET Status = 'Checked' WHERE Status = 'Unchecked'";
    $loop3 = pg_exec($connection, $update);

   echo "Success : Imechangiwa";


  $uploadNode = "INSERT into location_changed(userId,locationID,distance) VALUES ('$userID','$loc','$dist')";
    $loop2 = pg_exec($connection, $uploadNode);
echo "Success : Uploaded";
}
?>
