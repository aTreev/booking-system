<?php
namespace BookingSystem\Controller;

use BookingSystem\Model\RoomModel;
use DateTime;

class RoomController extends RoomModel {
    private $id;
    private $label;
    private $sleeps_adult;
    private $sleeps_child;
    private $description;
    private $bookings = array();
    private $unavailableDates = array();
    private $calendar = array();

    public function __construct()
    {
        //
    }

    private function setId($id) { $this->id = $id; return $this; }
    private function setLabel($label) { $this->label = $label; return $this; }
    private function setSleepsAdult($adults) { $this->sleeps_adult = $adults; return $this; }
    private function setSleepsChild($children) { $this->sleeps_child = $children; return $this; }
    private function setDescription($desc) { $this->description = $desc; return $this; }

    public function getId() { return $this->id; }
    public function getLabel() { return $this->label; }
    public function getBookings () { return $this->bookings; }
    public function getUnavailableDates() { return $this->unavailableDates; }

    public function getRoomsDetails()
    {
        return parent::getRoomDetailsModel();
    }

    public function initRoom($room) 
    {
        $this->
        setID($room['id'])->
        setLabel($room['label'])->
        setSleepsAdult($room['sleeps_adult'])->
        setSleepsChild($room['sleeps_child'])->
        setDescription($room['description']);

        $this->loadBookings();
        $this->checkUnavailableDates();
    }

    private function loadBookings()
    {
        $tmpController = new BookingController();
        $bookingData = $tmpController->getRoomBookings($this->id);

        foreach($bookingData as $booking) {
            $bookingController = new BookingController();
            $bookingController->initBooking($booking);
            array_push($this->bookings, $bookingController);
        }
    }

    private function checkUnavailableDates()
    {

        // iterate through the room's bookings
        foreach($this->bookings as $booking) {
            // Get the checkIn & checkOut dates
            $bookingStartDate = strtotime($booking->getCheckInDate()) ? strtotime($booking->getCheckInDate()) : DateTime::createFromFormat('Y-m-d', $booking->getCheckInDate())->getTimestamp();
            $bookingEndDate = strtotime($booking->getCheckOutDate()) ? strtotime($booking->getCheckOutDate()) - 86400 : DateTime::createFromFormat('Y-m-d', $booking->getCheckOutDate())->getTimestamp() - 86400; // checkout day - 1 day to allow for same day checkout/checkin

            // Iterate through the dates inbetween
            while ($bookingStartDate <= $bookingEndDate) {
                // Add date to the unavailableDates array
                array_push($this->unavailableDates, date("Y-m-d", $bookingStartDate));
                // Increment day by 1 day
                $bookingStartDate = strtotime("+1 day", $bookingStartDate);
            }
        }
    }



    //**************************************************************** */
    public function checkAvailable($checkInDate, $checkOutDate)
    {
        // Iterate through bookings
        foreach($this->bookings as $booking) {
            // convert checkIn/CheckOut dates to time
            $current = strtotime($booking->getCheckInDate());
            $last = strtotime($booking->getCheckOutDate()) - 86400; // checkout day - 1 day to allow for same day checkout/checkin

            $daysIterated = 0;
            // Iterate through the days
            while( $current <= $last ) {
                // Check for date match return false if found
                if (date("Y-m-d", $current) == $checkInDate) return false;
                // Skip check_in days for check_out day check
                if ($daysIterated > 0) {
                    if (date("Y-m-d", $current) == $checkOutDate) return false;
                }
                    
                $current = strtotime("+1 day", $current);
                $daysIterated++;
            }
        }
        // End of foreach, Room available, return true
        return true;
    }


}
?>