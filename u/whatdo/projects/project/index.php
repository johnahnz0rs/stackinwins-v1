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
// if ( !isset($_COOKIE['user_id']) or !isset($_COOKIE['username']) ) {
//     $headerString = 'Location: /u/signout';
//     header($headerString);
// }
include '../../components/validate-user.php';
validateUser();

include_once '../../components/header.php';
?>

<h1 style="color: black; margin: 72px;">lol this is body of /u/what-do/projects/</h1>

<?php include_once '../../components/footer.php';