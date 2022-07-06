function run() {
    prepareNavigation();    
}

function prepareNavigation() {
    const mobileNav = $("#nav-mobile");
    const bodyOverlay = $(".body-nav-overlay");
    const body = $("body");
    const hamburger = $("#hamburger");

    hamburger.click(function(){
        if (mobileNav.hasClass("show")) {
            mobileNav.removeClass("show");
            bodyOverlay.hide();
            body.removeClass("disable-scroll-y");
        } else {
            mobileNav.toggleClass("show");
            bodyOverlay.show();
            body.addClass("disable-scroll-y");
        }
        
        bodyOverlay.click(function(){
            bodyOverlay.hide();
            mobileNav.removeClass("show");
            body.removeClass("disable-scroll-y");
            $(this).off();
        });
    });
}

run();