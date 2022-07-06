<?php

use BookingSystem\Controller\Calendar;
use BookingSystem\Controller\Page;
use BookingSystem\View\HomeView;

if (strpos(realpath(""), "/home") !== false) $path = "/home/sites/5a/6/6b24cf0306/";
if (strpos(realpath(""), "C:\\") !== false) $path = "C:/wamp64/www/booking-system";
if (strpos(realpath(""), "/var") !== false) $path = "/var/www/booking-system";
require_once "$path/vendor/autoload.php";
require_once "$path/src/constants.php";


$starting_year = date("Y");
$startingMonth = date("m");

$page = new Page();

$homeview = new HomeView();
echo $page->displayPage($homeview->homePage());
?>
