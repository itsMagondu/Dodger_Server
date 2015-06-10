<?php

$connection = pg_pconnect("dbname=Dodger user=postgres password = a host=localhost");
if (!$connection) {
    print("Connection Failed.");
    exit;
} else {

    $currentTime = "SELECT LOCALTIMESTAMP";
    $timeQuery = pg_exec($connection, $currentTime);
    $timeCur = pg_fetch_result($timeQuery, 0, 0);
    echo "......Current time  = $timeCur...";

    $existingNodes = "SELECT * FROM location Where status = 'Unchecked' ";
    $loop1 = pg_exec($connection, $existingNodes);
//echo pg_num_rows($loop1);
    for ($lt = 0; $lt < pg_numrows($loop1); $lt++) {
        $locID = pg_result($loop1, $lt, 0);
        $userID = pg_result($loop1, $lt, 1);
        $long = pg_result($loop1, $lt, 2);
        $lat = pg_result($loop1, $lt, 3);
        $time = pg_result($loop1, $lt, 4);
        $status = pg_result($loop1, $lt, 5);

        echo ("......Latitude = $lat .. and Longitude = $long.....");


        $timeDifference = "SELECT DATE_PART('day', '$timeCur'::timestamp - '$time'::timestamp) * 24 +
              DATE_PART('hour', '$timeCur'::timestamp - '$timeCur'::timestamp);";
        $timeDiffernceQuery = pg_exec($connection, $timeDifference);
        $timeDiff = pg_fetch_result($timeDiffernceQuery, 0, 0);

        echo (".... The time difference  = $timeDiff............");

        if ($timeDiff > 1) {
            echo ("Stale data");
        } else {

            $closestNode = "SELECT itemid, ST_Distance(ST_Point(linearcs.x_cord, linearcs.y_cord),'POINT($long $lat)'::geometry) FROM
    linearcs WHERE ST_DWithin(ST_Point(linearcs.x_cord, linearcs.y_cord),'POINT($long $lat)'::geometry, 100) ORDER BY ST_Distance
    (ST_Point(linearcs.x_cord, linearcs.y_cord),'POINT($long $lat)'::geometry) LIMIT 1";

//Get the node on the road the current GPS coordinates are closest to
// $closestNode = "SELECT itemid FROM linearcs ORDER BY ST_Distance(ST_Point(linearcs.x_cord, linearcs.y_cord),'POINT($lat  $long)'::geometry)LIMIT 1";
            $loop2 = pg_exec($connection, $closestNode);
            //echo pg_num_rows($loop2);
            // pg_fetch_result($result, 0, 0);
            $loc = pg_fetch_result($loop2, 0, 0);
            echo "... Location : $loc ... ";
            
            $dist = pg_fetch_result($loop2, 0, 1);
            // $loc = $row['itemID'];
            echo "..... Distance: $dist ....";
            

            $update = "UPDATE location SET Status = 'Checked' WHERE Status = 'Unchecked'";
            $loop3 = pg_exec($connection, $update);

            echo "Success : Imechangiwa";


            $checkLastLocation = "SELECT locationid FROM location_changed Where status = 'Checked' and userid = '$userID' ORDER BY time DESC LIMIT  1 ";
            $lastLocQuery = pg_exec($connection, $checkLastLocation);
            $lastLoc = pg_fetch_result($lastLocQuery, 0, 0);
            echo "......Last Location  = $lastLoc...";

            if ($lastLoc == $loc || $lastLoc == null) {
                echo (".......Redundant data.....");
            } else 
                {

                $uploadNode = "INSERT into location_changed(userId,locationID,distance,status) VALUES ('$userID','$loc','$dist','Unchecked')";
                $loop2 = pg_exec($connection, $uploadNode);
                echo "Success : Uploaded";

                
            }
        }
    }
    include ("CalculateSpeed.php");
}
?>
