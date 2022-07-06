<?php 
namespace BookingSystem\Model;
use BookingSystem\Model\DatabaseConnection;

class UserModel 
{
    private static $DatabaseConnection;
    private $sql;
    private $stmt;

    // gets the singleton instance of the database connection
    protected function __construct() {
        self::$DatabaseConnection = DatabaseConnection::getInstance();
    }


    protected function getUserIds($style=MYSQLI_ASSOC) {
        // Ensure mysqli instance
        self::$DatabaseConnection = DatabaseConnection::getInstance();
        // Set SQL string
        $this->sql = "SELECT id FROM users;";
        // Returns a mysqli_stmt instance
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        // executes the mysqli_stmt
        $this->stmt->execute();
        // retrieves mysqli_result instance from the mysqli_stmt
        $result = $this->stmt->get_result();
        // fetches all results from mysqli_result instance as an assoc array
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function getExistingVerificationKeys($style=MYSQLI_ASSOC) {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "SELECT verification_key FROM users;";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function registerUser($id, $email, $passHash, $firstName, $surname, $dob, $vKey) {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql = "INSERT INTO users (`id`, `email`, `user_password`, `first_name`, `surname`, `dob`, `verification_key`) VALUES (?, ?, ?, ?, ?, ?, ?);";
        $this->stmt = self::$DatabaseConnection->prepare($this->sql);
        $this->stmt->bind_param("sssssss",$id, $email, $passHash, $firstName, $surname, $dob, $vKey);
        $this->stmt->execute();
        if($this->stmt->affected_rows!=1) {
            $errors="";
            if(strpos($this->stmt->error,'email')) {
                $errors.="Email address already exists<br />";
            }
            return $errors;
        } else {
            return $this->stmt->affected_rows;
        }
    }

    protected function getUserByEmailModel($email, $style=MYSQLI_ASSOC) {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

        $this->sql="SELECT * FROM users WHERE email = ?";
		$this->stmt = self::$DatabaseConnection->prepare($this->sql);
		$this->stmt->bind_param("s",$email);
		$this->stmt->execute();
		$result = $this->stmt->get_result();
		$resultset=$result->fetch_all($style);
		return $resultset;
    }

    protected function getUserByIdModel($id, $style=MYSQLI_ASSOC) {
        self::$DatabaseConnection = DatabaseConnection::getInstance();

		$this->sql="SELECT * FROM users WHERE id = ?;";
		$this->stmt = self::$DatabaseConnection->prepare($this->sql);
		$this->stmt->bind_param("s",$id);
		$this->stmt->execute();
		$result = $this->stmt->get_result();
		$resultset=$result->fetch_all($style);
		return $resultset;
	}
	
    /***************
     * Stores a user's new session on the DatabaseConnection
     ************************************/
	protected function storeSession($id, $session) {
        self::$DatabaseConnection = DatabaseConnection::getInstance();
        
		$this->sql="UPDATE users SET last_session = ? WHERE id = ?;";
		$this->stmt = self::$DatabaseConnection->prepare($this->sql);
		$this->stmt->bind_param("ss",$session,$id);
		$this->stmt->execute();
		return $this->stmt->affected_rows;
	}

}
?>