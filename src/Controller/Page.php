<?php

namespace BookingSystem\Controller;

use BookingSystem\Util\UniqueIdGenerator;
use BookingSystem\Util\Util;

$path = realpath("C:/") ? "C:/wamp64/www/jsp-cleaning" : "/var/www/jsp-cleaning";
//require_once "$path/src/constants.php";

/******************
 * The page class
 * This class handles authentication
 * and contains/controls state of classes & objects that are persistant
 * throughout the site. E.g. categories & cart
 ******************************/
class Page {
    private int $requiredAccessLevel;
    private bool $authenticated;
    private $rooms = array();

    /*************
     * Constructer for a page
     * Takes the page's required access level and uses the checkUser method to log user out
     * if they're on a page they shouldnt
     * Optional param @userFunctionsOnly
     * If true returns after the user and cart has been instantiated - 
     * prevents any further data loading
     **********/
    public function __construct($requiredAccessLevel=0, $userFunctionsOnly = false) {
        if (!isset($_SESSION)) session_start();
        $this->user = new UserController();
        $this->setRequiredAccessLevel($requiredAccessLevel);
        $this->setAuthenticated(false);
        $this->checkUser();

        if ($userFunctionsOnly) return;
    }


    private function setRequiredAccessLevel($requiredAccessLevel) { $this->requiredAccessLevel = $requiredAccessLevel; }
    private function setAuthenticated($isAuthenticated) { $this->authenticated = $isAuthenticated; }

    private function getRequiredAccessLevel() { return $this->requiredAccessLevel; }
    public function isAuthenticated() { return $this->authenticated; }
    public function getUser() { return $this->user; }
    public function getRooms() { return $this->rooms; }


    private function checkUser() 
    {
        
        // set guest session if userid not in session
        // This would occur on first time the user loads a page on a fresh browser
        // or after a logout
        if (!isset($_SESSION['userid'])) {
            $_SESSION['guest'] = true;
            $_SESSION['userid'] = "guest-".(new UniqueIdGenerator())->properties([], 15)->getUniqueId();
        }
        
        // check for and attempt to authenticate with session
        if ((isset($_SESSION['userid']) && $_SESSION['userid']!='' )) {
            $this->setAuthenticated($this->getUser()->authBySession($_SESSION['userid'], session_id()));
            // If auth end guest session
            if ($this->isAuthenticated()) $_SESSION['guest'] = false;
        } 
        
        // If we're still on a guest session at this point set the global user object's
        // userid to the guest-id. This allows the global user cart to be initialized for
        // the guest session.
        if (isset($_SESSION['guest']) && $_SESSION['guest'] == true) {
            // Check if guest userid is in cookie
            if (isset($_COOKIE['guest_session_userid']) && Util::valStr($_COOKIE['guest_session_userid'])) {
                // set session userid to cookie
                $_SESSION['userid'] = Util::sanStr($_COOKIE['guest_session_userid']);
            } else {
                // set 30 day cookie to new generated guest userid
                setcookie("guest_session_userid", $_SESSION['userid'],time()+60*60*24*30);
            }
            $this->getUser()->setGuestId($_SESSION['userid']);
        }
        
        
        // logout if access_level < page_req_access_level
        if ((!$this->isauthenticated() && $this->getRequiredAccessLevel()>0) || ($this->isAuthenticated() && $this->getUser()->getAccessLevel()<$this->getRequiredAccessLevel())) {
            $this->logout();
        }
    }

    public function login($email, $password, $location) 
    {
        $authenticated = 0;
        $redirect = ($location == "checkout") ? "/checkout" : "/user/account";

        // Get guest session userid and cart
        $guestid = $_SESSION['userid'];
        $cart = $this->getCart();

        // Generate a new session_id
		session_regenerate_id();

		if($this->getUser()->authByLogin($email,$password)) {
            // Transfer cart from guest session to user account if it has items
            if (!empty($cart->getItems())) $this->getCart()->transferCart($guestid, $this->getUser()->getId(), $cart->getId());
            
            // User authenticated by login Auth = true
			$authenticated = 1;
            // set the DB session to newly generated session_id
			$this->getUser()->storeSession($this->getUser()->getId(),session_id());
            // Set session['userid'] to userid
			$_SESSION['userid']=$this->getUser()->getId();
			$_SESSION['last_activity'] = time(); // init inactivity timer

		} 

        // return values for use in JavaScript
		return ['authenticated' => $authenticated, 'redirectLocation' => $redirect];
    }

    public function logout() 
    {
        if(isset($_SESSION['userid']) && $_SESSION['userid']!='') {
			$this->getUser()->storeSession($_SESSION['userid']);
		}
        session_unset();
        session_destroy();
        session_start();
        session_regenerate_id();
        header('location: /login');
        exit();
    }

   
    /*****************
     * Displays the page html
     * Takes in a view as parameter which can either be passed
     * as inline html or through a view class
     * 
     * Takes optional page title
     ******************************************/
    public function displayPage($view) 
    {
        if (array_key_exists('title',$view)) $viewTitle = $view['title']; else $viewTitle = "Ceol-na-mara";
        if (array_key_exists('style',$view)) $viewStyle = "<link rel='stylesheet' href='".$view['style']."' />"; else $viewStyle = "";
        if (array_key_exists("nav", $view)) $viewNav = $view['nav']; else $viewNav = "";
        if (array_key_exists('html',$view)) $viewHtml = $view['html']; else $viewHtml = "";
        if (array_key_exists('meta', $view)) $viewMetaTags = $view['meta']; else $viewMetaTags = array();
        
        $html = "
        <!doctype html>
        <html lang='en'>
            <head>
                <!-- Required meta tags -->
                <meta charset='utf-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1'>
                <!-- Site CSS -->
                <link rel='stylesheet' href='/assets/style/css/style.css'>
                $viewStyle
                <!-- Owl Carousel -->
                <link rel='stylesheet' href='/assets/owlCarousel/owl.carousel.min.css'>
                <link rel='stylesheet' href='/assets/owlCarousel/owl.theme.default.min.css'>
                <!-- Font awesome -->
                <script src='https://kit.fontawesome.com/1942d39d14.js' crossorigin='anonymous'></script>
                <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet'/>
                <!-- jQuery -->
                <script src='https://code.jquery.com/jquery-3.6.0.min.js' integrity='sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=' crossorigin='anonymous'></script>
                <!-- Google Fonts -->
                <link rel='preconnect' href='https://fonts.googleapis.com'>
                <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
                <link href='https://fonts.googleapis.com/css2?family=Work+Sans:wght@200;300;400;500;600;700&display=swap' rel='stylesheet'>
                <title>$viewTitle</title>
                <!-- Meta tags -->
                ";
                foreach($viewMetaTags as $tag) {
                    $html.=$tag;
                }
                $html.="

            </head>
            <body>
                $viewNav
                <div class='body-nav-overlay'></div>
                <main id='page-root'>
                    $viewHtml
                </main>
            </body>
            <script defer src='/assets/owlCarousel/owl.carousel.min.js'></script>
            <script defer src='/assets/js/formFunctions.js'></script>
            <script defer src='/assets/js/app.js'></script>
            ";
            // inject scripts
            if (array_key_exists('script',$view)) {
                if (!is_array($view['script'])) {
                    $html.="<script defer src='".$view['script']."'></script>";
                } else {
                    foreach($view['script'] as $script) {
                        $html.="<script defer src='".$script."'></script>";
                    }
                }
            }

           
        $html.="</html>";
        return $html;
    }
    
}

?>