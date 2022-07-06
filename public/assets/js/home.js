function startHomePage() {
    prepareBookingForm();
}



function prepareBookingForm() {
    const checkInInput = $("#check_in");
    const checkOutInput = $("#check_out");
    const checkInInputMobile = $("#check_in_mobile");
    const checkOutInputMobile = $("#check_out_mobile");

    checkInInput.change(function(){
        // Get date
        let date = new Date($(this).val())
        const day = 60 * 60 * 24 * 1000;

        // calculate min check_out date
        let minCheckout = new Date(date.getTime() + day);
        minCheckout = minCheckout.toISOString().split("T")[0];
        
        // set min and val to minCheckout
        checkOutInput.val(minCheckout);
        checkOutInput.attr("min", minCheckout);

    });    
    // Repeated because I don't want to change the markup again
    checkInInputMobile.change(function(){
        let date = new Date($(this).val())
        const day = 60 * 60 * 24 * 1000;

        let minCheckout = new Date(date.getTime() + day);
        minCheckout = minCheckout.toISOString().split("T")[0];

        checkOutInputMobile.val(minCheckout);
        checkOutInputMobile.attr("min", minCheckout);

    });    
}
startHomePage();