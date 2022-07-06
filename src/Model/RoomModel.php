<?php
namespace BookingSystem\Model;

use BookingSystem\Model\DatabaseConnection;

class RoomModel {
    private static $DatabaseConnection;
    private $sql;
    private $stmt;

    public function __construct() 
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();
    }

    protected function getRoomDetailsModel($style=MYSQLI_ASSOC)
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();
        $this->sql = "SELECT * FROM room;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset = $result->fetch_all($style);
        return $resultset;
    }
}
?>