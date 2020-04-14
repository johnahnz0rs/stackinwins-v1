<?php

/*
WHAT THIS POST SCRIPT DOES

- Section 1: validate login and method POST
- Section 2: gather static vars
- Section 3: add the new win
- Section 4: redirect to /u/dash
*/


// SECTION 1
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: ../signout');
}
if ( !isset($_COOKIE['user_id']) or !isset($_COOKIE['username']) or !isset($_POST['new-win']) ) {
    header('Location: ../signout');
}

// SECTION 2
require 'db.php';
$userId = $_COOKIE['user_id'];
$win = $_POST['new-win'];


$sqlAddNewWin = $db->prepare("
    INSERT INTO daily_wins (user_id, win) VALUES (:userId, :win)
");
try {
    $sqlAddNewWin->execute([
        'userId' => $userId,
        'win' => $win
    ]);
} catch (PDOException $e) {
    $output .= $e->getMessage();
    echo $output;
}

usleep(500000);
header('Location: /u/my-wins');
?>