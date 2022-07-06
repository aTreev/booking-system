function prepareBookingPage() {

    if ($("#check-in-input").val() != "") $(`[full-date=${$('#check-in-input').val()}]`).addClass("selected");
    if ($("#check-out-input").val() != "") $(`[full-date=${$('#check-out-input').val()}]`).addClass("selected");
    console.log($("#check-in-input").val())
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

    const availableDates = $("[date_available=true]");
    //fix this@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    availableDates.click(function(){
        const dateSelected = $(this);
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
            endDate = new Date($(this).attr("full-date"));

            // valid date
            if (endDate.getTime() > startDate.getTime()) {
                $("#check-out-input").val($(this).attr("full-date"));
                dateSelected.addClass("selected");
                hideCalendar();
            } else {
                // invalid date reset to 1 click
                $("[date_available=true]").removeClass("selected");
                clicks = 1;
            }
        }

        // First date selected, populate input with selected date
        if (clicks == 1) {
            availableDates.removeClass("selected");
            startDate = new Date($(this).attr("full-date"));
            $("#check-in-input").val($(this).attr("full-date"));
            $("#check-out-input").val("");
            dateSelected.addClass("selected");
        }
    });
}


function prepareCalendarBasicFunc() {
    const calendars = $(".calendar");
    const selectDateBtn = $("#select-dates");
    calendars.eq(0).addClass("current");
    calendars.eq(1).addClass("next");
    

    $("#calendar-left").click(function(){
        moveCalendar("prev");
    });

    $("#calendar-right").click(function(){
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
    const calendars = $(".calendar");

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

prepareBookingPage();