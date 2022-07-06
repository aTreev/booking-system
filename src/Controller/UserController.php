<?php 
namespace BookingSystem\Controller;
use BookingSystem\Model\UserModel;
use BookingSystem\Util\UniqueIdGenerator;
use BookingSystem\Util\UserHash;
use BookingSystem\View\UserView;

class UserController extends UserModel
{
    private $id;
	private $email; 
    private $userhash; 
	private $firstName;
    private $surname; 
    private $dob;
	private $lastSession; 
	private $accessLevel;
    private $verificationKey;
    private $verified;
    private $passResetKey;
    private $userView;

    private $addresses = [];
    private $orders = [];
    public function __construct() 
    {
        $this->id = -1;
        $this->email = "anon";
        $this->accessLevel = 0;
        $this->userhash=new UserHash();
        $this->userView = new UserView($this);
    }

    private function setId($id) { $this->id = $id; return $this; }
    public function setGuestId($id) { $this->id = $id; return $this; }
    private function setEmail($email) { $this->email = $email; return $this; }
    private function setFirstName($firstName) { $this->firstName = $firstName; return $this; }
    private function setSurname($surname) { $this->surname = $surname; return $this; }
    private function setSession($session) { $this->lastSession = $session; return $this; }
	private function setAccessLevel($accessLevel) { $this->accessLevel = $accessLevel; return $this; }
    private function setVerificationKey($vKey) { $this->verificationKey = $vKey; return $this; }
    private function setVerified($verified) { $this->verified = $verified; return $this; }
    private function setPassResetKey($resetKey) { $this->passResetKey = $resetKey; return $this;}
    private function setDOB($dob) { $this->dob = $dob; return $this; }

    // sets the userhash's value to a new hashed password
    private function setPass($password) 
    {
		$message="";
		if($this->userhash->checkRules($password)) {
			$this->userhash->newHash($password);
		} else {
			$message="Password did not meet complexity standards<br />";
		}
		return $message;

	}

    public function getId() { return $this->id; }
    public function getEmail() { return $this->email; }
    public function getFirstName() { return $this->firstName; }
	public function getSurname() { return $this->surname; }
    public function getDOB($format="Y-m-d") { return date($format, strtotime($this->dob)); }
    public function getSession() { return $this->lastSession; }
	public function getAccessLevel() { return $this->accessLevel; }
    public function isVerified() { return $this->verified; }
	public function getVerificationKey() { return $this->verificationKey; }
    public function getPassResetKey() { return $this->passResetKey; }
    public function getView() { return $this->userView = new UserView($this); }
    public function getAddresses() { return $this->addresses; }
    public function getOrders() { return $this->orders; }

    /*********
     * Authenticates a user by checking if the passed session
     * matches the session on the DB
     * If matches returns true/authenticated
     * else returns false/unauthenticated -- logout
     *************/
    public function authBySession($id, $session) 
    {
        $authenticated=false;
        // Check for existing user
		$authenticated=$this->getUserById($id);
        // return false if user doesn't exist
		if(!$authenticated) return $authenticated;
        // set authenticated to false if session doesn't match db session
		if($this->getSession()!=$session) $authenticated=false; 
        // return authenticated result
		return $authenticated;

    }

    /************
     * Authentication by login, first checks that a user exists
     * then tests the provided password through the hashing algo to look
     * for a match. If found and password matches returns true
     ***********************/
    public function authByLogin($email, $password) 
    {
        // Look for user
        $authenticated=$this->getUserByEmail($email);
        // If user check to see if password matches
		if($authenticated) {
			$authenticated=$this->userhash->testPass($password);
		}
		return $authenticated;
    }

    public function getUserByEmail($email) 
    {
		$haveuser=false;
		
		$data=parent::getUserByEmailModel($email);
		if(count($data)==1) {
			$user=$data[0];
			$haveuser=true;

			$this->setId($user["id"]);
			$this->setFirstname($user["first_name"]);
			$this->setSurname($user["surname"]);
			$this->setSession($user["last_session"]);
			$this->setEmail($user["email"]);
			$this->setDOB($user["dob"]);
			$this->setAccessLevel($user["access_level"]);
            $this->setVerificationKey($user["verification_key"]);
            $this->setVerified($user['verified']);
            $this->setPassResetKey($user['password_reset_key']);
            $this->userhash->initHash($user["user_password"]);

            $this->getUserAddresses();
            $this->getUserOrders();
		} 
		return $haveuser;
	}

    /*****************
     * Retrieves user by their id
     * Used with authentication by session_id()
     ************************************/
    public function getUserById($id) 
    {
		$haveuser=false;
		$data=parent::getUserByIdModel($id);
		if(count($data)==1) {
			$haveuser=true;
			$user=$data[0];
			$this->setId($user["id"]);
			$this->setFirstname($user["first_name"]);
			$this->setSurname($user["surname"]);
			$this->setSession($user["last_session"]);
			$this->setEmail($user["email"]);
			$this->setDOB($user["dob"]);
			$this->setAccessLevel($user["access_level"]);
            $this->setVerificationKey($user["verification_key"]);
            $this->setVerified($user['verified']);
            $this->setPassResetKey($user['password_reset_key']);
            $this->userhash->initHash($user["user_password"]);

            $this->getUserAddresses();
            $this->getUserOrders();
		} 
		return $haveuser;
	}

    /***************
     * Stores a user's session to the database
     * to be used once each time a user logs in
     ***********************/
    public function storeSession($id, $session="") 
    {
		$result=0;
		$result=parent::storeSession($id, $session);
		if($result) {$this->setSession($session);}
		return $result;
	}

    public function regUser($email, $password, $firstname,$surname, $dob) 
    {
        $uniqueIdGen = new UniqueIdGenerator();
        $userid = $uniqueIdGen->properties(parent::getUserIds())->getUniqueId();
        $verificationKey = $uniqueIdGen->properties(parent::getExistingVerificationKeys())->getUniqueId();

        $insert=0;
		$messages="";

        $this->setId($userid);
		$this->setEmail($email);
        $messages.=$this->setPass($password);		
        $this->setFirstname($firstname);
		$this->setSurname($surname);
		$this->setDOB($dob);
        $this->setVerificationKey($verificationKey);
		if($messages=="") {
			$insert=parent::registerUser($this->getId(), $this->getEmail(), $this->userhash->getHash(), $this->getFirstname(),$this->getSurname(), $this->getDOB(), $this->getVerificationKey());
			if($insert!=1) { $messages.=$insert;$insert=0; }
		}
		$result=['insert' => $insert,'messages' => $messages];
		return $result;
	}


    /***********
     * Retrieves user's saved addresses
     * pushes them to the address[] array
     *******/
    public function getUserAddresses()
    {
        $addressData = (new AddressController())->getUserAddressIds($this->getId());

        if (!$addressData)  return false;
        foreach($addressData as $address) {
            $addressContr = new AddressController();
            $addressContr->initAddressById($address['id'], $this->getId());
            array_push($this->addresses, $addressContr);
        }
    }

    public function getUserOrders()
    {
        $orderData = (new OrderController())->getUserOrderIds($this->getId());

        if (!$orderData) return false;

        foreach($orderData as $order) {
            $orderContr = new OrderController();
            $orderContr->initOrder($order['id'], $this->getId());
            array_push($this->orders, $orderContr);
        }
    }
}
?>