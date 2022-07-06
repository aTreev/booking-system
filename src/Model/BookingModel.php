<?php
namespace BookingSystem\Model;

class BookingModel {
    private static $DatabaseConnection;
    private $sql;
    private $stmt;

    public function getRoomBookingsModel($roomId, $style=MYSQLI_ASSOC)
    {
        $date = date("Y-m-d");
        self::$DatabaseConnection = DatabaseConnection::getInstance();
        $this->sql = "SELECT * FROM reservation WHERE room_id = ? AND check_in >= $date;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("i", $roomId);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset = $result->fetch_all($style);
        return $resultset;
    }
}
?>