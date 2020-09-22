<?php

session_start();

/*
- This is a script that handles POST form submission from /u/my-wins/edit?id=xyz
*/



// validate
if ( $_SERVER['REQUEST_METHOD'] != 'POST' or !isset($_COOKIE['user_id']) or !isset($_COOKIE['username']) or !isset($_POST['win']) or !isset($_POST['win-id']) ) {
    $headerString = 'Location: ../signout';
    header($headerString);
}


// requires & vars
require 'db.php';
$userId = $_COOKIE['user_id'];
$username = $_COOKIE['username'];
$winId = $_POST['win-id'];
$win = $_POST['win'];
$win = addslashes($win);


// sql
$sqlUpdateDailyWin = $db->prepare("UPDATE daily_wins SET win = '{$win}' WHERE user_id = {$userId} AND id = {$winId}");
try {
    $sqlUpdateDailyWin->execute();
} catch(PDOException $e) {
    $output .= $e->getMessage();
    echo $output;
    die();
}


// redirect
$headerString = 'Location: ../my-wins';
header($headerString);
