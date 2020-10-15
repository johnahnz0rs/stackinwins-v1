<?php

session_start();
date_default_timezone_set('America/Los_Angeles');

/**
 * the /u/what-do page is the dashboard for the whatDo automater
 *
 * 1. <a>see my projects</a> // default: collapsed on mobile; design project cards for desktop
 * 2. button: "what should i do now?" // click it to run a script
 *      - $projects = [proj1, proj2, proj3, ...];
 *      - $randomNum;
 *      - $doThis = $projects[$randomNum];
 *      - <span>{{ $doThis }}</span> + #3 below
 * 3. 2 options for user:
 *      - Button1: "OK, let's do it"
 *          - record in project_counts
 *          - show project detail screen
 *      - Button2: "Nah, something else"
 *          - pop/remove $doThis from $projects
 *          - re-run $whatShouldIDo()
 */

// // remove all session variables
// session_unset();

// // destroy the session
// session_destroy();

// validate user
if ( !isset($_COOKIE['user_id']) or !isset($_COOKIE['username']) ) {
    $headerString = 'Location: /u/signout';
    header($headerString);
}







/* SECTION 1 - check for login */



/* SECTION 2 - requires & vars */
require '../app/db.php';
// user info
$userId = $_COOKIE['user_id'];
$username = $_COOKIE['username'];
// ui
$dateToday = isset($_GET['date']) ? strval($_GET['date']) : strval(date('Y-m-d'));
setcookie('date_today', $dateToday, time()+120, '/');
$stackCount = 0;



include_once '../components/header.php';
?>

<style>
    .what-do-option {
        display: block;
        border: none;
        border-bottom: 1px solid #111;
        width: 100%;
        margin: 18px 0;
        padding: 12px 8px;
    }


</style>

<div id="page-whatdo-dash">
    <h1>What Do?</h1>

    <div id="whatdo-blurb" class="mx-auto mb-5 col-12 col-sm-10 col-md-6" style="font-size: 1.25em; font-style: italic; line-height: 0.97em; background-color: rgba(0,0,0,0.3); padding: 12px;">
        <p>
            Indecision is a form of perfectionism — which is, at its core, a fear of failure.
        </p>
        <p><strong>Kill that noise.</strong></p>
        <p>
            You know what needs to be done; pick one and let's go.
        </p>

    </div>


    <div class="mx-auto col-12 col-sm-10">
        <form id="form-what-do" class="mx-4 my-3">
            <input type="text" id="1" class="what-do-option" placeholder="Enter something you can do/work on" onkeyup="updateDos(1)">
        </form>

        <div class="mx-4 my-3 text-right">
            <a href="#" id="add-another-do"  class=""><em>Add another thing you can do</em></a>
        </div>
    </div>

    <div id="recommendation" class="mx-auto p-3 col-12 col-sm-10 text-center" style="display: none; background-color: gold;">
        <p>Do This:</p>
    </div>

    <div class="text-center my-5">
        <button id="button-what-do" class="btn btn-primary mx-auto" role="button">What Do?</button>
    </div>

    <!-- <div class="text-center my-5">
        <button id="print" class="btn btn-primary mx-auto" role="button">print whatDoItems</button>
    </div> -->

    <p>
        <a href="https://github.com/js-cookie/js-cookie">https://github.com/js-cookie/js-cookie</a>
        <br>
        <a href="https://stackoverflow.com/questions/1458724/how-do-i-set-unset-a-cookie-with-jquery">https://stackoverflow.com/questions/1458724/how-do-i-set-unset-a-cookie-with-jquery</a>
    </p>
<script>

let whatDoItems = {};
function updateDos(id) {
    whatDoItems[id] = $('#'+id).val();
    // console.log($('#'+id).val());
}

$(document).ready(function() {

    const $whatDoForm = $('#form-what-do');
    const $whatDo = $('#button-what-do');


    // prevent the page from reloading
    $whatDoForm.submit(function(e) {
        e.preventDefault();
    });

    // add another input when requested by user
    $('#add-another-do').click(function(e) {
        e.preventDefault();
        var count = 1 + $('#form-what-do > input').length;
        $('<input type="text">')
            .addClass('what-do-option')
            .attr('id', count)
            .attr('placeholder', 'Enter something you can do/work on')
            .attr('onkeyup', 'updateDos(' + count + ')')
            .appendTo($whatDoForm);
        $('#'+count).select();
    });

    // $('.what-do-option').select(function() {
    //     console.log('you selected');
    // });
    // $('#'+count).focus(function() {
    //     console.log('you focused');
    // });


    // tell me: whatDo?
    $whatDo.click(function() {
        const count = Object.keys(whatDoItems).length;
        const randomNum = Math.floor((Math.random() * count) + 1);
        $('#recommendation').toggle();
        $('#recommendation').append('<p>' + whatDoItems[randomNum] + '</p>');
        $whatDo.attr('disabled', 'disabled');
    });

    // dev - print whatDoItems
    $('#print').click(function() {
        console.log(whatDoItems);
    });

});



</script>


</div> <!-- end #page-what-do-dash -->







<script>

</script>

<?php
include_once '../components/footer.php';