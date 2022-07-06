<?php
namespace BookingSystem\Util;

class UserHash {
	private $hashed;
	private $options=['cost' => 10,];
	
	public function __construct() {	}
	
	private function setHash($hash){$this->hashed=$hash;}
	public function getHash(){return $this->hashed;}

    
    public function initHash($hash) {
		$this->setHash($hash);
	}

    /**********
     * Takes plaintext password, runs it through the php default hashing algo
     * and sets the instance variable to the hashed password
     * @params
     * @plainText - user's plaintext password max 72 chars
     ***************/
    public function newHash($plainText) {
		$this->setHash(password_hash($plainText,PASSWORD_DEFAULT,$this->options));
	}

    /*************
     * Tests authentication by passing a plaintext through the
     * same hashing algorithm to check for a match
     **********/
    public function testPass($plainText) {
		return password_verify($plainText,$this->getHash());
	}

    public function checkRules($password) {
		$valid=true;
		if(strlen(trim($password))<8 || strlen(trim($password))>72) {$valid=false;}
		return $valid;
	}

}
/*
$newhash = new UserHash();
$newhash->newHash('Pa$$w0rd');
var_dump($newhash);
if($newhash->checkRules('Pa$$w0rd')) {
	echo "<br />Password ok";
} else {
	echo "<br />Password not ok";
}
*/
?>
