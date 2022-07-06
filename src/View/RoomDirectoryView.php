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
            $html.="<div class='booking-page-form container'>";
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
                    $html.="<i class='fa-solid fa-angle-left' id='calendar-left'></i>";
                    foreach($this->controller->getCalendar() as $calendar) {
                        $html.= $calendar->view();
                    }
                    $html.="<i class='fa-solid fa-angle-right' id='calendar-right'></i>";
                $html.="</div>";
            $html.="</div>";

        $html.="</div>";
        
        // then display results if a date was passed
        if ($this->controller->getCheckInDate() != null) {
            $html.="<p>{$this->controller->getCheckInDate() }</p>";
        }
        return [
            'title' => $title,
            'meta' => $meta,
            'style' => $style,
            'nav' => $nav,
            'html' => $html,
            'script' => $script
        ];
    }
}
?>