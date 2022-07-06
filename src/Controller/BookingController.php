<?php
namespace BookingSystem\Controller;

use BookingSystem\Model\BookingModel;

class BookingController extends BookingModel {
    private $id;
    private $roomId;
    private $customerName;
    private $customerTel;
    private $total;
    private $checkInDate;
    private $checkOutDate;

    public function __construct()
    {
        // Not used
    }

    private function setId($id) { $this->id = $id; return $this; }
    private function setRoomId($roomId) { $this->roomId = $roomId; return $this; }
    private function setCustomerName($name) { $this->customerName = $name; return $this; }
    private function setCustomerTel($tel) { $this->customerTel = $tel; return $this; }
    private function setTotal($total) { $this->total = $total; return $this; }
    private function setCheckedInDate($date) { $this->checkInDate = $date; return $this; }
    private function setCheckedOutDate($date) { $this->checkOutDate = $date; return $this; }

    public function getCheckInDate() { return $this->checkInDate; }
    public function getCheckOutDate() { return $this->checkOutDate; }

    public function initBooking($data)
    {
        $this->
        setId($data['id']) ->
        setRoomId($data['room_id']) ->
        setCustomerName($data['customer_name']) ->
        setCustomerTel($data['customer_tel']) ->
        setTotal($data['total']) ->
        setCheckedInDate($data['check_in']) ->
        setCheckedOutDate($data['check_out']);
    }

    public function getRoomBookings($roomId)
    {
        return parent::getRoomBookingsModel($roomId);
    }

}
?>