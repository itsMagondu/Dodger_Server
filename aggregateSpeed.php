<?php

$connection = pg_pconnect("dbname=Dodger user=postgres password = a host=localhost");
if (!$connection) {
    print("Connection Failed.");
    exit;
}
else

    $update = "UPDATE traffic SET status = 'Checked' WHERE status = 'Unchecked'";
     $upda = pg_exec($connection, $update);
     
    $currentNodes = "SELECT speed,recid,speedid FROM speeds Where status = 'Unmapped'";
$loop1 = pg_exec($connection, $currentNodes);
//echo pg_num_rows($loop1);
for ($lt = 0; $lt < pg_numrows($loop1); $lt++) {
    $speed = pg_result($loop1, $lt, 0);
    $rec = pg_result($loop1, $lt, 1);
    $speedid = pg_result($loop1, $lt, 2);

   
	$update = "UPDATE speeds SET status = 'Mapped' WHERE status = 'Unmapped' and speedid = '$speedid'";
        $update = pg_exec($connection, $update);
	echo "Success : Imechangiwa";

	$count = 1;
	$others = "SELECT speed,speedid FROM speeds Where status = 'Unmapped' and recid = '$rec'";
        $getOthersSpeed = pg_exec($connection, $others);
        $num = pg_numrows($getOthersSpeed);
        echo $num;
        if ($num > 1)
        {
	for ($lt = 0; $lt < pg_numrows($getOthersSpeed); $lt++)
	{
    $speedOther = pg_result($others, $lt, 0);
	$speedid = pg_result($loop1, $lt, 1);
	$speed = $speed + $speedOther;
	
	$update = "UPDATE speed SET Status = 'Mapped' WHERE Status = 'Unmapped' where speedid = '$speedid'";
    $updateSpeed = pg_exec($connection, $update);
	echo "... The total Speed is .... $speed....";
	$count++;
    
	}
      }
       else
            {
            echo (".......No result.....");
            }
   
   $averageSpeed = $speed/$count ;
   
   
  $insert = "INSERT into traffic(recid,speed,status) VALUES ('$rec','$averageSpeed','Unchecked')";
    $insertSpeed = pg_exec($connection, $insert);
	

}

?>
