const checkInInput = $("#check-in-input");
const checkOutInput = $("#check-out-input");
const availableDates = $("[date_available=true]");
const calendars = $(".calendar");

function prepareBookingPage() {

    if (checkInInput.val() != "") $(`[full-date=${$('#check-in-input').val()}]`).addClass("selected");
    if (checkOutInput.val() != "") $(`[full-date=${$('#check-out-input').val()}]`).addClass("selected");
    prepareCalendarBasicFunc();
    prepareCalendarDateSelection();
}




/******************
 * The following Calendar code is currently a proof of concept
 * and can / will be further optimized for re-usability
 *********/
function prepareCalendarDateSelection() {
    let clicks = 0;
    let startDate = "";
    let endDate = "";

    //fix this@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    availableDates.click(function(){
        const selectedDate = $(this);
        clicks++;

        // Third date selected reset to 1 click
        if (clicks > 2) {
            availableDates.removeClass("selected");
            $("#check-out-input").val("");
            $("#check-out-input").val("");
            clicks = 1;
        }

        // Second date selected check for valid dates
        if (clicks == 2) {
            endDate = new Date(selectedDate.attr("full-date"));

            // valid date
            if (endDate.getTime() > startDate.getTime()) {
                checkOutInput.val(selectedDate.attr("full-date"));
                selectedDate.addClass("selected");
                getAvailability(startDate, endDate);
                hideCalendar();
            } else {
                // invalid date reset to 1 click
                availableDates.removeClass("selected");
                clicks = 1;
            }
        }

        // First date selected, populate input with selected date
        if (clicks == 1) {
            availableDates.removeClass("selected");
            startDate = new Date($(this).attr("full-date"));
            checkInInput.val($(this).attr("full-date"));
            checkOutInput.val("");
            selectedDate.addClass("selected");
        }
    });
}


function prepareCalendarBasicFunc() {
    const selectDateBtn = $("#select-dates");
    const leftBtn = $("#calendar-left");
    const rightBtn = $("#calendar-right");

    calendars.eq(0).addClass("current");
    calendars.eq(1).addClass("next");
    

    leftBtn.click(function(){
        moveCalendar("prev");
    });

    rightBtn.click(function(){
        moveCalendar("next");
    });

    selectDateBtn.click(function(ev){
        ev.preventDefault();
        showCalendar();
    });


}

function showCalendar() {
    $(".calendar-container").addClass("show");

    $(document).click(function(ev) {
        if (ev.target.classList.contains("booking-page-container")) {
            $(".calendar-container").removeClass("show");
            $(this).off();
        }
    });
}

function hideCalendar() {
    $(".calendar-container").removeClass("show");
}

function moveCalendar(direction) {
    calendars.each(function(){
        if ($(this).hasClass("current") && direction == "next" && $(this).next().next().hasClass("calendar")) {

            $(this).removeClass("current");
            $(this).next().removeClass("next");

            $(this).next().addClass("current");
            $(this).next().next().addClass("next");
            return false;
        }

        if ($(this).hasClass("current") && direction == "prev" && $(this).prev().hasClass("calendar")) {
            $(this).removeClass("current");
            $(this).addClass("next");

            $(this).next().removeClass("next");

            $(this).prev().addClass("current");
            return false;
        }
    })
}

function getAvailability(checkIn, checkOut) {
    return new Promise(function(resolve){
        checkIn = checkIn.toISOString().split("T")[0];
        checkOut = checkOut.toISOString().split("T")[0];

        $.ajax({
            url: "/ajax-scripts/availability-check.php",
            method: "POST",
            data: {
                checkIn: checkIn,
                checkOut: checkOut
            }
        }).done(function(result){
            console.log(JSON.parse(result));
            $('#room-results-container').html(JSON.parse(result));
        });
    })
}
prepareBookingPage();