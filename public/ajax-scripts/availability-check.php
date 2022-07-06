<?php

use BookingSystem\Controller\RoomDirectory;
use BookingSystem\Util\Util;

if (strpos(realpath(""), "/home") !== false) $path = "/home/sites/5a/6/6b24cf0306/";
if (strpos(realpath(""), "C:\\") !== false) $path = "C:/wamp64/www/booking-system";
if (strpos(realpath(""), "/var") !== false) $path = "/var/www/booking-system";
require_once "$path/vendor/autoload.php";
require_once "$path/src/constants.php";


$checkIn = Util::sanStr($_POST['checkIn']);
$checkOut = Util::sanStr($_POST['checkOut']);

$roomDirectory = new RoomDirectory();
$roomDirectory->bookingRequest($checkIn, $checkOut, 0);

echo json_encode($roomDirectory->getView()->availableRooms());

?>