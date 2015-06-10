<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$connection = pg_pconnect("dbname=Dodger user=postgres password = a host=localhost");
if (!$connection) {
    print("Connection Failed.");
    exit;
}
else
//    $myresult = pg_exec($connection, "SELECT * FROM route_arc where name like '%Westlands Peponi Roads%'");
////echo pg_num_rows($myresult);SELECT * FROM route_arc where name like '%Westlands Peponi Roads%'"
//$count = 0;
//for ($lt = 0; $lt < pg_numrows($myresult); $lt++) {
//    $id = pg_result($myresult, $lt, 0);
//    $username = pg_result($myresult, $lt, 1);
//    $fname = pg_result($myresult, $lt, 2);
//      $lname = pg_result($myresult, $lt, 3);
//      $count ++;
//    // print results
//    print("Num: $count \t");
//    print("gid: $id \t");
//    print("length_m: $username \t");
//    print("fnode: $fname \t");
//    print("tnode: $lname<br />\n");

//$query = "SELECT route_node.nodeid As Node, route_node.x_cord As Lat, route_node.y_cord As Lon
//
//    FROM route_node As Nodes
//    WHERE g1.gid = 1 and g1.gid <> g2.gid AND ST_DWithin(g1.the_geom, g2.the_geom, 300)
//    ORDER BY ST_Distance(g1.the_geom,g2.the_geom)
//    LIMIT 5";
//
//$myquery = pg_exec($connection,$query);

//$query = "SELECT nodeid from route_node where y_cord = -1.23612463 AND x_cord = 36.79313278 ";
//$query ="
//    SELECT a.x_cord,a.y_cord, the_edge FROM
//    (
//    SELECT ST_Distance(e.the_edge, 'POINT(-1.23612463 36.79313278)'::geometry)
//    as dist, the_edge
//    FROM( SELECT ST_MakeLine(ST_Point(a.x_cord, a.y_cord),ST_Point(b.x_cord, b.y_cord)) AS the_edge
//    FROM route_node AS a INNER JOIN route_node AS b ON b.gid=(a.gid+1)) e) s
//    ORDER BY dist LIMIT 1";
$query = "SELECT itemid FROM linearcs
ORDER BY ST_DWithin(ST_Point(linearcs.x_cord, linearcs.y_cord),'POINT(-1.23612463 36.79313278)'::geometry)
ORDER BY ST_Distance(ST_Point(linearcs.x_cord, linearcs.y_cord),'POINT(-1.23612463 36.79313278)
LIMIT 3";
$myquery = pg_exec($connection,$query);
echo pg_num_rows($myquery);
for ($lt = 0; $lt < pg_numrows($myquery); $lt++) {
    $edge = pg_result($myquery, $lt, 0);
    
    print("Edge: $edge \n");
    
    }
//echo pg_result($myquery, 0);

?>
