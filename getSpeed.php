<?php
include 'dbConnect.php';

if (!$connection) {
    print("Connection Failed.");
    exit;
}
else
{
$query = "SELECT trafficid,recid, speed FROM traffic WHERE status = 'Unchecked' ";
$myquery = pg_exec($connection, $query);



for ($lt = 0; $lt < pg_numrows($myquery); $lt++) {
    $id = pg_result($myquery, $lt, 0);
    $rec = pg_result($myquery, $lt, 1);
    $speed = pg_result($myquery, $lt, 2);

   
    $getNodes = "SELECT x_cord, y_cord FROM linearcs WHERE recid = '$rec'";
    $nodes = pg_exec($connection, $getNodes);

    while ($row = pg_fetch_assoc($nodes)) {

      //  $output = array('x_cord' => $row['x_cord'],'y_cord' => $row['y_cord'], 'speed' => $speed );

       // $json = array2json($output);
        $output['x_cord'] = $row['x_cord'];
        $output['y_cord'] = $row['y_cord'];
        $output['rec'] = $rec;
        $output['speed'] = $speed;
        $data[] = $output;

       // $json = array2json

        
    }
    
}
print(json_encode($data));
}
?>
