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
    private $displayImage;
    private $bookings = array();
    private $unavailableDates = array();
    private $calendar = array();

    public function __construct()
    {
        //
    }

    private function setId($id) { $this->id = $id; return $this; }
    private function setLabel($label) { $this->label = $label; return $this; }
    private function setPrice($price) { $this->price = $price; return $this; }
    private function setDescription($desc) { $this->description = $desc; return $this; }
    private function setDisplayImage($img) { $this->displayImage = $img; return $this; }

    public function getId() { return $this->id; }
    public function getLabel() { return $this->label; }
    public function getPrice() { return $this->price; }
    public function getDisplayImage() { return $this->displayImage; }
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
        setPrice($room['price'])->
        setDescription($room['description'])->
        setDisplayImage($room['display_image']);

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
            $bookingEndDate = strtotime($booking->getCheckOutDate()) ? strtotime($booking->getCheckOutDate()) : DateTime::createFromFormat('Y-m-d', $booking->getCheckOutDate())->getTimestamp(); // checkout day - 1 day (-86400) to allow for same day checkout/checkin

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
    // Logic
    public function checkAvailable($checkInDate, $checkOutDate)
    {
        // get start and end date
        $requestedDates = array();
        $start = strtotime($checkInDate);
        $end = strtotime($checkOutDate);

        // add all dates in between to the array
        while($start <= $end) {
            array_push($requestedDates, date("Y-m-d", $start));
            $start = strtotime("+1 day", $start);
        }

        // Return false if any dates match
        if (array_intersect($this->unavailableDates, $requestedDates)) return false;

        return true;
    }


}
?>