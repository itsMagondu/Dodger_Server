<?php

include 'dbConnect.php';

if (!$connection) {
    print("Connection Failed.");
    exit;
} else {
    $currentTime = "SELECT LOCALTIMESTAMP";
    $timeQuery = pg_exec($connection, $currentTime);
    $timeCur = pg_fetch_result($timeQuery, 0, 0);

    $query = "SELECT time FROM events ";
    $myquery = pg_exec($connection, $query);

    $getQuery = "SELECT time FROM events ";
    $gQuery = pg_exec($connection, $getQuery);

    for ($lt = 0; $lt < pg_numrows($myquery); $lt++) {
        $time = pg_result($loop1, $lt, 0);

        $timeDifference = "SELECT DATE_PART('day', '$timeCur'::timestamp - '$time'::timestamp) * 24 +
              DATE_PART('hour', '$timeCur'::timestamp - '$timeCur'::timestamp);";
        $timeDiffernceQuery = pg_exec($connection, $timeDifference);
        $timeDiff = pg_fetch_result($timeDiffernceQuery, 0, 0);

        echo (".... The time difference  = $timeDiff............");

        if ($timeDiff > 1) {
            echo ("Stale data");
        } else {

            while ($row = pg_fetch_assoc($gQuery)) {


                $data[] = $row;
            }
            print(json_encode($data));
        }
    }
}
?>
