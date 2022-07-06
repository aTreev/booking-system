<?php
namespace BookingSystem\View;

class HomeView {
    private $seasonStart;
    private $seasonEnd;


    public function __construct()
    {
        $this->loadSeasonSettings();
    }

    private function loadSeasonSettings()
    {
        // get starting season
    }

    public function homePage()
    {
        $title = "";
        $meta = array();
        $style = "";
        $nav = "";
        $html = "";
        $script = "/assets/js/home.js";

        $nav.= SharedView::headerHomepage();

        $html.="<div id='booking-section-container' class='container'>";
            $html.="<div class='booking-section' id='booking-section'>";
                $html.="<div class='labels'>";
                    $html.="<label for='check_in'>Check In</label>";
                    $html.="<label for='check_out'>Check Out</label>";
                    $html.="<label for='guests'>Guests</label>";
                $html.="</div>";
                $html.="<form class='inputs' action='/bookings' method='GET'>";
                    $html.="<input type='date' id='check_in' name='check_in' min='".date("Y-m-d")."'>";
                    $html.="<input type='date' id='check_out' name='check_out' min='".date("Y-m-d")."'>";
                    $html.="<select id='guests' name='guests'>";
                        $html.="<option>1</option>";
                        $html.="<option>2</option>";
                        $html.="<option>3</option>";
                    $html.="</select>";
                    $html.="<button>Book Now</button>";
                $html.="</form>";
            $html.="</div>";
            
            $html.="<div class='booking-section-mobile' id='booking-section'>";
                $html.="<form action='/bookings' method='GET'>";
                    $html.="<div class='form-50-container'>";
                        $html.="<div class='form-item'>";
                            $html.="<label for='check_in_mobile'>Check In</label>";
                            $html.="<input type='date' id='check_in_mobile' name='check_in' min='".date("Y-m-d")."'>";
                        $html.="</div>";
                        $html.="<div class='form-item'>";
                            $html.="<label for='check_out_mobile'>Check Out</label>";
                            $html.="<input type='date' id='check_out_mobile' name='check_out' min='".date("Y-m-d")."'>";
                        $html.="</div>";
                    $html.="</div>";
                    $html.="<div class='form-item'>";
                        $html.="<label for='guests_mobile'>Guests</label>";
                        $html.="<select id='guests_mobile' name='guests'>";
                            $html.="<option>1</option>";
                            $html.="<option>2</option>";
                            $html.="<option>3</option>";
                        $html.="</select>";
                    $html.="</div>";
                    $html.="<div class='form-item'>";
                        $html.="<button>Book Now</button>";
                    $html.="</div>";

                $html.="</form>";
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
}

?>