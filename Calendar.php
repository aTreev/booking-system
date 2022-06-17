<?php

class Calendar {
    private $month;
    private $year;
    public $num_days;
    private $date_info;
    public $day_of_week;
    private $day_headlines = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
    private $unavailable_dates = array();
    private $current_date_of_month;

    public function __construct($month, $year, $unavailable_dates) {
        $this->month = $month;
        $this->year = $year;
        $this->num_days = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
        $this->date_info = getDate(strtotime(mktime(0,0,0, $this->month, 1, $this->year)));
        $this->day_of_week = $this->date_info['wday'];
        $this->unavailable_dates = $unavailable_dates;
        $this->current_date_of_month = $this->checkForCurrentDate();
    }

    private function checkForUnavailability() {

    }

    private function checkForCurrentDate() {
        if ($this->month == date("m") && $this->year == date("Y")) {
           return date("d");
        } else {
            return 0;
        }
    }

    public function view() {
        // Start table with caption
        $html = "<table class='calendar'>";
        $html.="<caption>".(DateTime::createFromFormat('!m', $this->month))->format('F')." ".$this->year."</caption>";

        // Start header row
        $html.="<thead><tr>";
        // Iterate through day headlines
        foreach($this->day_headlines as $day) {
            $html.="<th>$day</th>";
        }
        // End header row
        $html.="</tr></thead>";

        // Start First body row
        $html.="<tbody>";
        $html.="<tr>";
        
        // add colspan to first row if starting day isn't Sun
        if ($this->day_of_week > 0) {
            $html.="<td colspan='".$this->day_of_week."'></td>";
        }

        // Set day index to start at 1 to match numDaysInMonth
        $day_index = 1;
        $count_days_unavailable = count($this->unavailable_dates);

        // Iterate from index til numDaysInMonth reached
        while($day_index <= $this->num_days) {
            // Reset day of week counter and close / reopen row
            if ($this->day_of_week == 7) {
                $this->day_of_week = 0;
                $html.="</tr><tr>";
            }
            
            /*
            * Check for current date of month and unavailable dates
            * 
            */

            // Previous days
            if ($day_index <= $this->current_date_of_month) $html.="<td style='color:grey;'>$day_index</td>";
            else {
                // Base day html
                $day_html = "<td>$day_index</td>";

                // Check for unavilable and rewrite html
                for($i = 0; $i < $count_days_unavailable; $i++) {
                    if ($this->unavailable_dates[$i] == $day_index) {
                        $day_html = "<td style='color:red;'>$day_index</td>";
                    } 
                }
                // Add final day type to overall html
                $html.= $day_html;
            }

            // Increment counters
            $day_index++;
            $this->day_of_week++;
        }
        // End while

        // If counter stops before day of week is equal to 7
        // Calculate the required colspan to pad the table
        if ($this->day_of_week != 7) {
            $remaining_days = 7 - $this->day_of_week;
            $html.="<td colspan='$remaining_days'></td>";
        }

        // End final row and table
        $html.="</tr>";
        $html.="</tbody>";
        $html.="</table>";

        return $html;
    }
}
?>