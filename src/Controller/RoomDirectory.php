<?php
namespace BookingSystem\Controller;

use BookingSystem\Controller\RoomController;
use BookingSystem\View\RoomDirectoryView;

class RoomDirectory {

    private $checkInDate;
    private $checkOutDate;
    private $guestCount;
    private $view;
    private $unavailableDates = array();
    private $calendar = array();

    public $rooms = array();
    private $availableRooms = array();


    public function __construct()
    {
        $this->loadRooms();
        $this->calculateAvailability();
        $this->constructCalendar();
    }

    public function bookingRequest($checkInDate, $checkOutDate, $guests) {
        $this->setCheckInDate($checkInDate);
        $this->setCheckOutDate($checkOutDate);
        $this->setGuestCount($guests);
        $this->checkAvailability();
    }
    
    private function setCheckInDate($checkInDate) { $this->checkInDate = $checkInDate; }
    private function setCheckOutDate($checkOutDate) { $this->checkOutDate = $checkOutDate; }
    private function setGuestCount($guests) { $this->guestCount = $guests; }

    public function getCheckInDate() { return $this->checkInDate; }
    public function getCheckOutDate() { return $this->checkOutDate; }
    public function hasAvailability() { return $this->availableRooms; }
    public function getGuestCount() { return $this->guestCount; }
    public function getCalendar() { return $this->calendar; }
    public function getView() { return $this->view = new RoomDirectoryView($this); }

    private function loadRooms()
    {
        $tmpController = new RoomController();
        
        $roomData = $tmpController->getRoomsDetails();

        foreach($roomData as $room) {
            $roomController = new RoomController();
            $roomController->initRoom($room);
            array_push($this->rooms, $roomController);
        }
    }

    private function checkAvailability() 
    {
        foreach($this->rooms as $room) {
            $roomAvailable = $room->checkAvailable($this->checkInDate, $this->checkOutDate);
            if ($roomAvailable) array_push($this->availableRooms, $room);
        }
        return $this->availableRooms;
    }


    private function calculateAvailability()
    {
        $bookingDates = array();
        $unavailableDates = array();
        $countRooms = count($this->rooms);

        // Iterate through all rooms
        foreach($this->rooms as $room) {
            // Iterate through room booking dates
            foreach($room->getUnavailableDates() as $date) {
                // add room booking date to bookingDates array
                array_push($bookingDates, $date);
            }
        }
       
        // Iterate through bookingDates array
        foreach($bookingDates as $date) {
            // get the occurence of each date
            $count = count(array_keys($bookingDates, $date));
            // If count of date matches number of rooms that date is fully booked
            if ($count == $countRooms) {
                // Add to unavailableDates array
                array_push($unavailableDates, $date);
            }
        }
        // Filter out duplicates due to the way this function works
        $this->unavailableDates = array_unique($unavailableDates);
    }


    private function constructCalendar()
    {
        // Get current Month & Year
        $currentMonth = ltrim(date("m"), "0");
        $seasonEndMonth = constant("season_end_month");
        $currentYear = date("Y");
    
        // If we're not in B&B season set calendar starting month to the set season start
        if ($currentMonth < constant("season_start_month")) $currentMonth = constant("season_start_month");

        // Iterate through the current year
        for ($i = $currentMonth; $i <= $seasonEndMonth; $i++) {

            $monthUnavailableDates = array();

            // Iterate through the unavailable dates
            foreach($this->unavailableDates as $date) {
                if (( date("m", strtotime($date)) == $i) && (date("Y", strtotime($date)) == $currentYear) ) {
                    // If date matches current month and year add to the months unavailable dates array
                    array_push($monthUnavailableDates, $date);
                }
            }
            // Create new calendar for month with the unavailable dates
            $calendarController = new Calendar($i, $currentYear, $monthUnavailableDates);

            // Add calendar to room's calendar array
            array_push($this->calendar, $calendarController);
        }

        // Add the entirety of the following year to the calendar
        for ($i = constant("season_start_month"); $i <= $seasonEndMonth; $i++) {

            $monthUnavailableDates = array();

            foreach($this->unavailableDates as $date) {
                if (date("m", strtotime($date)) == $i && date("Y", strtotime($date)) == $currentYear+1) {
                    array_push($monthUnavailableDates, $date);
                }
            }
            $calendarController = new Calendar($i, $currentYear+1, $monthUnavailableDates);
            array_push($this->calendar, $calendarController);
        }

    }
}
?>