<?php
include "Calendar.php";

$starting_year = date("Y");
$startingMonth = date("m");
$months_left = 12 - date("m");


// For each month eventually..
// taken from database :: SELECT * FROM bookings where check_in LIKE %year-month%
$bookings = array(
    ['id' => 1, 'check_in' => "2022-06-22", 'check_out' => "2022-06-24"],
    ['id' => 2, 'check_in' => "2022-06-28", 'check_out' => "2022-06-29"],
);

$unavailable_dates = array();

foreach($bookings as $booking) {
    $start = strtotime($booking['check_in']);
    $end = strtotime($booking['check_out']);

    while ($start < $end) {
        array_push($unavailable_dates,date("d", $start));
        $start = strtotime("+1 day", $start);
    }
}


$calendar = new Calendar(6, 2022, $unavailable_dates);
echo $calendar->view();

/*
for($i = $startingMonth; $i <= 12; $i++) {
    $calendar = new Calendar($i, 2022);
    echo $calendar->view();
    echo "<br>";
}

echo "2023<br>";
for($i = 1; $i <= 12; $i++) {
    $calendar = new Calendar($i, $starting_year+1);
    echo $calendar->view();
    echo "<br>";
}
*/
?>

<style>
    .calendar {
        padding: 20px;
        
    }
    .calender > thead > tr > th {
        background-color: black;
        color: white;
    }
</style>