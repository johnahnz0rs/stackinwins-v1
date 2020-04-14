<?php


/*
WHAT THIS SCRIPT DOES

- This is a script that handles POST form submission from /u/my-wins/edit?id=xyz
- Section 1: validate POST and validate login
- Section 2: gather static vars
- Section 3: SQL update
- Section 4: set msg (as apropo) and redirect to /u/my-wins
*/



// SECTION 1
session_start();
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: ../signout');
}
if ( !isset($_COOKIE['user_id']) or !isset($_COOKIE['username']) or !isset($_POST['win']) or !isset($_POST['win-id']) ) {
    header('Location: ../signout');
}


// SECTION 2
require 'db.php';
$userId = $_COOKIE['user_id'];
$username = $_COOKIE['username'];
$win = $_POST['win'];
$winId = $_POST['win-id'];



// SECTION 3
$sqlUpdateDailyWin = $db->prepare("
    UPDATE daily_wins
    SET win = :win
    WHERE user_id = :userId AND id = :winId
");
try {
    $sqlUpdateDailyWin->exceute([
        'win' => $win,
        'userId' => $userId,
        'winId' => $winId
    ]);
} catch(PDOException $e) {
    $output .= $e->getMessage();
    echo $output;
}


// SECTION 4
// setcookie('win-manipulate-msg', 'Your WIN has been updated', 7, '/');
// usleep(500000);
header('Location: ../my-wins');



