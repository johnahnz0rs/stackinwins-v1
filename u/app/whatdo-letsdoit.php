<?php

session_start();
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo 'failed at request method';
    header('Location: ../signout');
}

// if ( !isset($_COOKIE['user_id']) or !isset($_COOKIE['username']) or !isset($_POST['user-id']) or !isset($_POST['win-id']) ) {
//     header('Location: ../signout');
// }


if ($_COOKIE['user_id'] != $_POST['userId']) {
    echo 'failed at user validation';
    header('Location: ../signout');
}

$userId = $_POST['userId'];
$winId = $_POST['winId'];
$dailyWinId = $_POST['dailyWinId'];

$returnString = 'userId: ' . $userId . ', winId: ' . $winId . ', dailyWinId: ' . $dailyWinId;
echo $returnString;
// return true;
