<?php

use BookingSystem\Util\Util;

if (strpos(realpath(""), "/home") !== false) $path = "/home/sites/5a/6/6b24cf0306/";
if (strpos(realpath(""), "C:\\") !== false) $path = "C:/wamp64/www/booking-system";
if (strpos(realpath(""), "/var") !== false) $path = "/var/www/booking-system";
require_once "$path/vendor/autoload.php";
require_once "$path/src/constants.php";

if (Util::valStr($_GET['room_id']) && Util::valStr($_GET['check_in']) && Util::valStr($_GET['check_out'])) {
    echo "lets make a booking";
} else {
    echo "no booking details provided";
}

?>