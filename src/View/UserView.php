<?php
namespace BookingSystem\View;


class UserView 
{
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }


    public function register()
    {
        $title = "Registration | Getwhisky";
        $script = "/assets/js/registration.js";
        $html = 
        "
        <div class='col m-auto p-5 mt-5 bg-white rounded border' style='max-width:700px;'>
            <h1 class='mb-0 fs-3'>New Customer</h1>
            <p class='text-muted'>Enter your details below</p>
            <form id='regForm'>
                <div class='mb-3'>
                    <label for='email'>Email Address</label>
                    <input type='email' class='form-control' name='email' id='email'>
                </div>
                <div class='mb-3'>
                    <label for='first-name'>First name</label>
                    <input type='text' class='form-control' name='firstname' id='first-name'>
                </div>
                <div class='mb-3'>
                    <label for='surname'>Surname</label>
                    <input type='text' class='form-control' name='surname' id='surname'>
                </div>
                <div class='mb-3'>
                    <label for='dob'>Date of birth</label>
                    <input type='date' class='form-control' name='dob' id='dob'>
                </div>
                <div class='mb-3'>
                    <label for='password'>Password</label>
                    <input type='password' class='form-control' name='password' id='password'>
                </div> 
                <div class='mb-3'>
                    <label for='repeat-password'>Repeat Password</label>
                    <input type='password' class='form-control' name='repeat_password' id='repeat_password'>
                </div> 
                <input type='submit' class='btn btn-primary' id='reg_submit' value='Submit'>
            </form>
        </div>
        ";
        return ['html' => $html, 'title' => 'Registration | Getwhisky', 'script' => $script];
    }

    public function login()
    {
        $title = "Login | Getwhisky";
        $script = "/assets/js/login-page.js";
        $html = 
        "
        <div class='login-container row p-4 bg-white rounded border mt-5 m-auto' style='max-width:700px;'>

        <div class='login-form col p-3'>
            <h1 class='fs-3 pb-2'>Login</h1>
            <form id='login-form'>
                <div class='mb-3'>
                    <label for='login_email'>Email Address</label>
                    <input type='email' class='form-control' name='email' id='login_email'>
                </div>
                <div class='mb-3'>
                    <label for='login_password'>Password</label>
                    <input type='password' class='form-control' name='password' id='login_password'>
                </div> 
                <p class='mb-3'>New to Getwhisky? <a href='/register'>Sign up here</a></p>
                <input type='submit' class='btn btn-primary' value='Submit'>
            </form>
        </div>
        ";
        return ['html' => $html, 'title' => $title, 'script' => $script];
    }


    public function sidebar($activePage="profile")
    {
        // add active class depending on page
        $html = 
        "
        <div class='sidebar'>
            <div class='sidebar-option'>
                <a href='/user/details' class='link-icon'><i class='p-2 fa-regular fa-user'></i></a>
                <a href='/user/details' class='link'>My details</a>
            </div>
            <div class='sidebar-option'>
                <a href='/user/addresses' class='link-icon'><i class='p-2 fa-regular fa-address-book'></i></a>
                <a href='/user/addresses' class='link'>My delivery Addresses</a>
            </div>
            <div class='sidebar-option'>
                <a href='/user/orders' class='link-icon'><i class='p-2 fa-solid fa-box'></i></a>
                <a href='/user/orders' class='link'>My orders</a>
            </div>
            <div class='sidebar-option sign-out'>
                <a href='/logout' class='link-icon'><i class='p-2 fa-solid fa-arrow-right-from-bracket'></i></a>
                <a href='/logout' class='link'>Sign Out</a>
            </div>
        </div>
        ";
        return $html;
    }

    public function index()
    {
        $html = "";
        $style = "/assets/style/user-page.css";
        $script = "";
        $title = "My Account | Getwhisky";

        $html.=SharedView::backwardsNavigation(array(
            ['url' => "/user/account",'pageName' => "My Account"]
        ));

        $html.="
        <div class='user-root'>
            <!-- Sidebar -->
            <div class='sidebar-container'>
                ".$this->sidebar()."
            </div>
            <!-- Main content -->
            <div class='main-content'>

                <!-- Account Header -->
                <div class='header' style='background-color:#ecedee;'>
                    <div class='top'>
                        <h1 class='fs-3 mb-0'>Hello ".$this->user->getFirstName()." ".$this->user->getSurname()."</h1>
                        <a class='logout' href='/logout' class='text-danger text-decoration-none'>Sign out</a>
                    </div>
                    <p>Welcome to your acccount profile, select from one of the options below to manage your account</p>
                </div>


                <div class='account-options-container'>

                    <div class='account-option' style='width:48%;position: relative;'>
                        <a href='/user/details' class='wrapper-link'><span></span></a>
                        <div class='top'>
                            <i class='fas fa-user profile-icon'></i>
                        </div>
                        <div class='bottom'>
                            <h5>My details</h5>
                            <p>Manage and edit your account details</p>
                        </div>  
                    </div>

                    <div class='account-option col bg-white border flex-fill' style='width:48%;position: relative;'>
                        <a href='/user/addresses' class='wrapper-link'><span></span></a>
                        <div class='top'>
                            <i class='fas fa-truck profile-icon'></i>
                        </div>
                        <div class='bottom'>
                            <h5>My Addresses</h5>
                            <p>Manage your delivery address details</p>
                        </div>  
                    </div>

                    <div class='account-option col bg-white border flex-fill' style='width:48%;position: relative;'>
                        <a href='/user/orders' class='wrapper-link'><span></span></a>
                        <div class='top'>
                            <i class='fas fa-box-open profile-icon'></i>
                        </div>
                        <div class='bottom'>
                            <h5>My orders</h5>
                            <p>View your order history</p>
                        </div>  
                    </div>
                    

                </div>
            </div>
        </div>
        ";
        
        return [
            'html' => $html, 
            'style' => $style, 
            'script' => $script, 
            'title' => $title
        ];
    }


    public function addressPage()
    {
        $html = "";
        $script = "/assets/js/address-page.js";
        $style = "/assets/style/user-page.css";
        $title = "My addresses | Getwhisky";

        $html.=SharedView::backwardsNavigation(array(
            ['url' => "/user/account", 'pageName' => "My account"],
            ['url' => "", "pageName" => "My addresses"]
        ));
        
        $html.="<div class='user-root'>";
            $html.="<!-- Sidebar -->";
            $html.="<div class='sidebar-container'>";
                $html.=$this->sidebar();
            $html.="</div>";

            $html.="<!-- Main content -->";
            $html.="<div class='main-content'>";

                $html.="<div class='header' style=''>";
                    $html.="<h5>My addresses</h5>";
                $html.="</div>";

                $html.="<div class='user-address-root'>";

                if (empty($this->user->getAddresses())) {
                    $html.="<p class='text-muted ps-4'>You currently have no saved addresses!</p>";
                }
                foreach ($this->user->getAddresses() as $address) {
                    $html.=$address->getView()->addressItem();
                }

                $html.="</div>";
                $html.="<div class='add-address-btn'>";
                    $html.="<p>Add new address +</p>";
                $html.="</div>";
            $html.="</div>";

        $html.="</div>";

        $html.= (new AddressController())->getView()->createAddressForm();
        return [
            'html' => $html,
            'script' => $script,
            'style' => $style,
            'title' => $title,
        ];
    }

    public function orderPage()
    {
        $html = "";
        $title = "My Orders | Getwhisky";
        $script = "";
        $style = "/assets/style/user-page.css";

        $orders = $this->user->getOrders();

        $html.=SharedView::backwardsNavigation(array(
            ['url' => "/user/account", 'pageName' => "My account"],
            ['url' => "", "pageName" => "My Orders"]
        ));

        $html.="<div class='user-root'>";

            $html.="<!-- Sidebar -->";
            $html.="<div class='sidebar-container'>";
                $html.=$this->sidebar();
            $html.="</div>";

            $html.="<!-- Main content -->";
            $html.="<div class='main-content'>";
                $html.="<div class='header'>";
                    $html.="<h5>My orders</h5>";
                $html.="</div>";


                // Order root
                $html.="<div class='user-order-root'>";

                foreach($orders as $order) {
                    // Required variables
                    $totalWithDelivery = number_format($order->getTotal() + $order->getDeliveryCost(), 2, ".", "");
                    $datePlaced = date("d F Y", strtotime($order->getDatetimePlaced()));

                    // Order start
                    $html.="<div class='order-item'>";

                        // Order header
                        $html.="<div class='order-item-header'>";
                            // Left -- Order details
                            $html.="<div class='left'>";
                                $html.="<div class='order-item-column'>";
                                    $html.="<p class='title'>Date placed</p>";
                                    $html.="<p>{$datePlaced}</p>";
                                $html.="</div>";
                                $html.="<div class='order-item-column'>";
                                    $html.="<p class='title'>Total</p>";
                                    $html.="<p>£{$totalWithDelivery}</p>";
                                $html.="</div>";
                                $html.="<div>";
                                    $html.="<p class='title'>Delivery</p>";
                                    $html.="<p>{$order->getDeliveryRecipient()}</p>";
                                $html.="</div>";
                            $html.="</div>";
                            // Right -- Order number
                            $html.="<div class='right'>";
                                $html.="<p>Order #".sprintf('%08d', $order->getId())."</p>";
                            $html.="</div>";
                        $html.="</div>";

                        // Order items
                        $html.="<div class='items'>";
                        foreach($order->getItems() as $item) {
                            // Required variables
                            $itemName = ucwords($item['product_name']);
                            $itemPrice = number_format($item['price_paid'], "2", ".", "");
                            $itemSubtotal = number_format($item['price_paid'] * $item['quantity'], "2", ".", "");
                            $productLink = str_replace(" ", "-", $item['product_name']);
                            // Item
                            $html.="<div class='item'>";
                            // Image / name
                            $html.="<div class='left'>";
                                $html.="<div class='wrapper-link-container'>";
                                $html.="<img src='{$item['product_image']}' style='max-width: 125px;'>";
                                $html.="<a href='/products/?p={$productLink}'><span class='wrapper-link'></span></a>";
                                $html.="</div>";
                                $html.="<div class='item-details'>";
                                    $html.="<p><a class='item-name' href='/products/?p={$productLink}'>{$itemName}</a></p>";
                                    $html.="<p class='item-qty'>Quantity: {$item['quantity']}</p>";
                                $html.="</div>";
                            $html.="</div>";
                            // Price / subtotal
                            $html.="<div class='right'>";
                                $html.="<p><span class='title'>Price:</span> £{$itemPrice}</p>";
                                $html.="<p><span class='title'>Subotal:</span> £{$itemSubtotal}</p>";
                            $html.="</div>";
                            $html.="</div>";
                        }
                        $html.="</div>";

                    $html.="</div>";
                }

                $html.="</div>";
            
            $html.="</div>";

        $html.="</div>";
        
        return [
            'html' => $html,
            'title' => $title,
            'style' => $style,
            'script' => $script
        ];
    }
}
?>