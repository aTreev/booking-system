<?php
namespace BookingSystem\View;


class RoomDirectoryView {
    private $controller;

    public function __construct($controller)
    {
        $this->controller = $controller;
    }


    public function bookingPage()
    {
        $title = "";
        $meta = array();
        $style = "/assets/style/css/booking-page.css";
        $nav = "";
        $html = "";
        $script = "/assets/js/booking-page.js";

        $nav.= SharedView::defaultHeader();

        // display booking form section
        $html.="<div class='booking-page-container'>";
            $html.="<div class='container-lg main-container'>";

                $html.="<div class='left'>";
                    $html.="<div class='booking-page-form'>";
                        $html.="<form>";
                            $html.="<div class='input-label-container'>";
                                $html.="<label for='check-in-input'>Check In</label>";
                                $html.= "<input type='date' readonly id='check-in-input' value='".$this->controller->getCheckInDate()."'/>";
                            $html.="</div>";
                            $html.="<div class='input-label-container'>";
                                $html.="<label for='check-out-input'>Check Out</label>";
                                $html.= "<input type='date' readonly id='check-out-input' value='".$this->controller->getCheckOutDate()."'/>";
                            $html.="</div>";
                            $html.="<button id='select-dates'>Select Dates</button>";
                        $html.="</form>";
                        $html.="<div class='calendar-container'>";
                            $html.= "<div id='calendar-loader' class='loader-container'><img class='loader' src='/assets/img/loader.gif' /></div>";
                            $html.="<i class='fa-solid fa-angle-left' id='calendar-left'></i>";
                            foreach($this->controller->getCalendar() as $calendar) {
                                $html.= $calendar->view();
                            }
                            $html.="<i class='fa-solid fa-angle-right' id='calendar-right'></i>";
                        $html.="</div>";
                    $html.="</div>";

                    $html.="<div class='room-results-container' id='room-results-container'>";
                        $html.= $this->availableRooms();
                    $html.="</div>";
                $html.="</div>";

                $html.="<div class='right'>";
                    $html.="<div class='info-section'>";
                        $html.="<img src='/assets/img/ceol-na-mara-house.jpg' />";
                        $html.="<div class='info-item'><p>Check In: ".constant("check_in_time")."</p></div>";
                        $html.="<div class='info-item'><p>Check Out: ".constant("check_out_time")."</p></div>";
                        $html.="<div class='info-item'><p>".constant("contact_number")."</p></div>";
                        $html.="<div class='info-item'><p></p></div>";
                    $html.="</div>";
                $html.="</div>";
            $html.="</div>";
        $html.="</div>";

        return [
            'title' => $title,
            'meta' => $meta,
            'style' => $style,
            'nav' => $nav,
            'html' => $html,
            'script' => $script
        ];
    }

    public function availableRooms()
    {
        $html = "";
     
        if ($this->controller->hasAvailability()) {
            $availableRooms = $this->controller->getAvailableRooms();
            foreach($availableRooms as $room) {
                $html.="<div class='room-item' room-id='{$room->getId()}'>";

                    $html.="<div class='top'>";
                        $html.="<p class='room-label'>{$room->getLabel()}</p>";
                        $html.="<p class='room-sleeps'>Sleeps {$room->getSleeps()}</p>";
                    $html.="</div>";

                    $html.="<div class='bottom'>";
                        $html.="<div class='left'>";
                            $html.="<img src='{$room->getDisplayImage()}' />";
                        $html.="</div>";
                        $html.="<div class='right'>";
                            $html.="<p class='room-price'>Price £".$room->getPrice() * $this->controller->getBookingLength()."</p>";
                            $html.="<p class='room-description'>{$room->getDescription()}</p>";
                        $html.="</div>";
                    $html.="</div>";

                $html.="</div>";
         
            }
        } 
        else {
            $html.="<div class='no-rooms-message'>";
                $html.="<p>No availability found for selected dates, please try another date.</p>";
            $html.="</div>";
        }
        
        return $html;

    }
}
?>