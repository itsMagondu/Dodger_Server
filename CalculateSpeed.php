<?php

function distance_haversine($lat, $lon, $latNow, $lonNow) {
  global $earth_radius;
  global $delta_lat;
  global $delta_lon;
  $alpha    = $delta_lat/2;
  $beta     = $delta_lon/2;
  $a        = sin(deg2rad($alpha)) * sin(deg2rad($alpha)) + cos(deg2rad($latNow)) * cos(deg2rad($lat)) * sin(deg2rad($beta)) * sin(deg2rad($beta)) ;
  $c        = asin(min(1, sqrt($a)));
  $distance = 2*$earth_radius * $c;
  $distance = round($distance, 4);

  return $distance;
}

$connection = pg_pconnect("dbname=Dodger user=postgres password = a host=localhost");
if (!$connection) {
    print("Connection Failed.");
    exit;
}
else
    $currentNodes = "SELECT * FROM location_changed Where status = 'Unchecked'";
$loop1 = pg_exec($connection, $currentNodes);
//echo pg_num_rows($loop1);
for ($lt = 0; $lt < pg_numrows($loop1); $lt++) {
    $locchangedID = pg_result($loop1, $lt, 0);
    $userID = pg_result($loop1, $lt, 1);
    $locID = pg_result($loop1, $lt, 2);
    $time = pg_result($loop1, $lt, 3);
    $dist = pg_result($loop1, $lt, 4);
    $status = pg_result($loop1, $lt, 5);

//    print("           Loc: $locchangedID \n");
//    print("           User: $userID \n");
//    print("           Long: $locID \n");
//    print("           Lat: $dist \n");
//    print("           Time: $time \n");
//    print("           Stat: $status \n");

    $getlastNode = "SELECT x_cord,y_cord,recid FROM linearcs Where itemid = '$locID'";
    $getLastCoordinates = pg_exec($connection, $getlastNode);

    $lon= pg_fetch_result($getLastCoordinates,0,0);
    $lat = pg_fetch_result($getLastCoordinates,0,1);
    $rec = pg_fetch_result($getLastCoordinates,0,2);
    echo "........Current Coordinates:... $lon and $lat .... RecId is $rec ... ";


   
    $lastNode = "SELECT locationid,time FROM location_changed Where status = 'Checked' and userid = '$userID' ORDER BY time DESC LIMIT 1 ";
    $getLocID = pg_exec($connection, $lastNode);
   $prevLoc = pg_fetch_result($getLocID,0,0);
   $timePrev = pg_fetch_result($getLocID,0,1);

    echo "...........Previous Location = $prevLoc... and time = $timePrev ...............";
    
   
   $getNowNode = "SELECT x_cord,y_cord FROM linearcs Where itemid = '$prevLoc'";
    $getNowCoordinates = pg_exec($connection, $getNowNode);
    $lonNow= pg_fetch_result($getNowCoordinates,0,0);
    $latNow = pg_fetch_result($getNowCoordinates,0,1);
    echo "...Prev Location : $lonNow $latNow....";

$earth_radius = 6378100; # in meters

$delta_lat = $lat - $latNow ;
$delta_lon = $lon - $lonNow ;



$hav_distance = distance_haversine($lat, $lon, $latNow, $lonNow);
 echo ".....Haversine distance covered in meters = $hav_distance....." ;
   
//     $distCovered = "SELECT Transform (ST_Distance('POINT($latNow  $lonNow)'::geometry *110 ), Transform ('POINT($lat  $lon)'::geometry *110 ))";
//     $getDistance = pg_exec($connection, $distCovered);
//     $calculatedDist= pg_fetch_result($getDistance,0,0);
//
//     echo ".....Distance covered  = $distCovered....." ;

     $timePassed =  "SELECT (DATE_PART('hour', '$time'::time - '$timePrev'::time) * 60 +
               DATE_PART('minute', '$time'::time - '$timePrev'::time)) * 60 +
               DATE_PART('second', '$time'::time - '$timePrev'::time);";
     $getTime = pg_exec($connection, $timePassed);
     $calculatedTime= pg_fetch_result($getTime,0,0);

     echo ".....Time passed in Seconds is  = $calculatedTime....." ;
        
     $speed = $hav_distance/$calculatedTime ;

     echo "....The speed is $speed .... ";

    $uploadNode = "INSERT into speeds(fLat,fLon,tLat,tLon,speed,status,recid) VALUES ('$lat','$lon','$latNow','$lonNow','$speed','Unmapped', $rec)";
    $getcurrentcoordinates = pg_exec($connection, $uploadNode);
    echo "Success : Uploaded";

    $update = "UPDATE location_changed SET Status = 'Checked' WHERE Status = 'Unchecked' and loc_changedid = '$locchangedID'";
    $loop3 = pg_exec($connection, $update);
   echo "Success : Imechangiwa";

include ("AggregateSpeed.php");
  
}
?>
