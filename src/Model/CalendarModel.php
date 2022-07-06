<?php
namespace BookingSystem\Model;

use BookingSystem\Model\DatabaseConnection;

class CalendarModel 
{
    private static $DatabaseConnection;
    private $sql;
    private $stmt;

    // gets the singleton instance of the database connection
    protected function __construct() 
    {
        self::$DatabaseConnection = DatabaseConnection::getInstance();
    }


    protected function getUnavailableDates($year, $month, $style=MYSQLI_ASSOC) 
    {
        $parameter = "%".$year."-".sprintf("%02d", $month)."%";
        self::$DatabaseConnection = DatabaseConnection::getInstance();
        $this->sql = "SELECT * FROM reservation WHERE check_in LIKE ?;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("s", $parameter);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset = $result->fetch_all($style);
        return $resultset;
    }
}
?>