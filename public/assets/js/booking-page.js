// Fix the global/local state mess

const checkInInput = $("#check-in-input");
const checkOutInput = $("#check-out-input");
const availableDates = $("[date_available=true]");
const calendars = $(".calendar");

function prepareBookingPage() {

    if (checkInInput.val() != "") {
        $(`[full-date=${$('#check-in-input').val()}]`).addClass("selected");
    }
    if (checkOutInput.val() != "") {
        $(`[full-date=${$('#check-out-input').val()}]`).addClass("selected");
    }
    if (checkInInput.val() != "" && checkOutInput.val() != "") {
        prepareRoomLinks(checkInInput.val(), checkOutInput.val());  
    }

    prepareCalendarBasicFunc();
    prepareCalendarDateSelection();
}




/******************
 * The following Calendar code is currently a proof of concept
 * and can / will be further optimized for re-usability
 * 
 * 
 * Checks how many clicks has occurred on the calendar
 * 1st click - Check in selection, resets calendar and highlights check in date
 * 2nd click - Check out date selection, check for valid date then load html for available rooms and call the prepareRoomLinks function
 * 3rd click - Remove all selection highlighting, reset values and set clicks to 1 to make this click a check in selection
 *********/
function prepareCalendarDateSelection() {
    let clicks = 0;
    let startDate = "";
    let endDate = "";

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

            // valid date check for available rooms
            if (endDate.getTime() > startDate.getTime()) {
                checkOutInput.val(selectedDate.attr("full-date"));
                selectedDate.addClass("selected");

                getAvailability(startDate, endDate).then(function(response){
                    $('#calendar-loader').show();
                    setTimeout(() => {
                        $('#room-results-container').html(response.html);
                        prepareRoomLinks(startDate, endDate)
                        hideCalendar();
                        $('#calendar-loader').hide();
                    }, 500);
                });

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

    // Change this to make closing the calendar more user friendly
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
            resolve(JSON.parse(result));
        });
    })
}


/**********
 * Adds links to booking page to each room with the checkInDate and checkOutDate
 */
function prepareRoomLinks(checkInDate, checkOutDate) {
    const rooms = $(".room-item");

    rooms.each(function(){
        const roomId = $(this).attr("room-id");

        $(this).click(function(){
            window.location.href = `/booking?room_id=${roomId}&check_in=${checkInDate}&check_out=${checkOutDate}`;
        });
    });
}
prepareBookingPage();