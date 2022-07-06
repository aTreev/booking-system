<?php
namespace BookingSystem\View;

class SharedView {

    private static $nav_links = array(
        ['title' => "Home", 'link' => "/"],
        ['title' => "About", 'link' => "/about"],
        ['title' => "Contact Us", 'link' => "/contact-us"],
        ['title' => "Book Now", 'link' => "/bookings"]
    );

    public static function headerHomepage() {
        $html = "";

        $html.= "<header id='homepage-banner' class='homepage-banner'>";
        $html.= "<div class='nav-container ignore-overlay container'>";
            $html.="<div>";
            $html.="</div>";
            $html.="<div class='nav-links-desktop'>";
                foreach(self::$nav_links as $nav_item) {
                    $html.="<a href='{$nav_item['link']}'>{$nav_item['title']}</a>";
                }
            $html.="</div>";
            $html.="<div class='hamburger-container' id='hamburger'>";
                $html.="<i class='hamburger fa-solid fa-bars'></i>";
            $html.="</div>";

        $html.="</div>";
        $html.= "<div class='content-container'>";
            $html.="<h1>Ceol na Mara Bed & Breakfast</h1>";
            $html.="<p>With us you can expect superb sea and mountain views, rooms with private bathrooms and a hearty Highland breakfast. All within walking range of the sandy Staffin beach and pier.</p>";
            $html.="<a href='#booking-section-container' class='link-btn'>Book Now</a>";
        $html.= "</div>";
        $html.="</header>";
        
        $html.= self::mobileNav();

        return $html;
    }

    public static function defaultHeader()
    {
        $html = "";
        
        $html.="<header class='standard-nav'>";
            $html.="<nav class='container'>";
                $html.="<div class='left'>";
                    $html.="<h2>".constant("app_name_short")."</h2>";
                $html.="</div>";
                $html.="<div class='right'>";
                    foreach(self::$nav_links as $nav_item) {
                        $html.="<a href='{$nav_item['link']}'>{$nav_item['title']}</a>";
                    }
                $html.="</div>";
                $html.="<div class='hamburger-container' id='hamburger'>";
                    $html.="<i class='hamburger fa-solid fa-bars'></i>";
                $html.="</div>";
            $html.="</nav>";
        $html.="</header>";
        $html.= self::mobileNav();
    
        return $html;
    }
    

    private static function mobileNav()
    {
        $html = "";
        $html.="<nav class='nav-links-mobile ignore-overlay' id='nav-mobile'>";
        foreach(self::$nav_links as $nav_item) {
            $html.="<a href='{$nav_item['link']}'>{$nav_item['title']}</a>";
        }
        $html.="</nav>";
        return $html;
    }
}
?>