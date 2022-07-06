<?php

use BookingSystem\Controller\Page;
use BookingSystem\Controller\RoomDirectory;
use BookingSystem\Util\Util;

if (strpos(realpath(""), "/home") !== false) $path = "/home/sites/5a/6/6b24cf0306/";
if (strpos(realpath(""), "C:\\") !== false) $path = "C:/wamp64/www/booking-system";
if (strpos(realpath(""), "/var") !== false) $path = "/var/www/booking-system";
require_once "$path/vendor/autoload.php";
require_once "$path/src/constants.php";


// if no dates passed show booking form
$page = new Page();
$roomDirectory = new RoomDirectory();

if (isset($_GET['check_in']) && Util::valStr($_GET['check_in']) 
    && isset($_GET['check_out']) && Util::valStr($_GET['check_out']) 
    && isset($_GET['guests']) && Util::valStr($_GET['guests'])) {
    $check_in = Util::sanStr($_GET['check_in']);
    $check_out = Util::sanStr($_GET['check_out']);
    $guests = Util::sanStr($_GET['guests']);
    $roomDirectory->bookingRequest($_GET['check_in'], $_GET['check_out'], $_GET['guests']);
    //if ($roomDirectory->hasAvailability()) echo "available";
    //else echo "unavailable";


}

echo $page->displayPage($roomDirectory->getView()->bookingPage());



?>

