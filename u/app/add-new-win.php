<?php

/*
Add a new dailyWin to user
*/


// validate
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $headerString = 'Location: ../signout';
    header($headerString);
}
if ( !isset($_COOKIE['user_id']) or !isset($_COOKIE['username']) or !isset($_POST['new-win']) ) {
    $headerString = 'Location: ../signout';
    header($headerString);
}

// requires & vars
require 'db.php';
$userId = $_COOKIE['user_id'];
$win = $_POST['new-win'];
$win = addslashes($win);

// sql
$sqlAddNewWin = $db->prepare("INSERT INTO daily_wins (user_id, win) VALUES ({$userId}, '{$win}')");
try {
    $sqlAddNewWin->execute();
} catch (PDOException $e) {
    $output .= $e->getMessage();
    echo $output;
    die();
}

$headerString = 'Location: /u/my-wins';
header($headerString);
?>