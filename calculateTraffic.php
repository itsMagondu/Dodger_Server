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

    $closestNode = "SELECT itemid FROM linearcs WHERE ST_DWithin(ST_Point(linearcs.x_cord, linearcs.y_cord),'POINT($lat  $long)'::geometry, 300) ORDER BY ST_Distance(ST_Point(linearcs.x_cord, linearcs.y_cord),'POINT($lat  $long)'::geometry)
LIMIT 1";
    // $closestNode = "SELECT itemid FROM linearcs ORDER BY ST_Distance(ST_Point(linearcs.x_cord, linearcs.y_cord),'POINT($lat  $long)'::geometry)LIMIT 1";
    $loop2 = pg_exec($connection, $closestNode);
    //echo pg_num_rows($loop2);
    for ($lt = 0; $lt < pg_numrows($loop2); $lt++)
    {
        $loc = pg_result($loop2, $lt, 0);

        print("         Edge: $loc \n");
    }
}
?>
