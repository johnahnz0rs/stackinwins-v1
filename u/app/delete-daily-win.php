<?php


/*
WHAT THIS SCRIPT DOES

- This is a script that handles POST form submission from /u/my-wins/delete?id=xyz
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
if ( !isset($_COOKIE['user_id']) or !isset($_COOKIE['username']) or !isset($_POST['user-id']) or !isset($_POST['win-id']) ) {
    header('Location: ../signout');
}
if ($_COOKIE['user_id'] != $_POST['user_id']) {
    header('Location: ../signout');
}


// SECTION 2
require 'db.php';
$winId = $_POST['win-id'];
$userId = $_POST['user-id'];

// echo $winId . ', ' . $userId;
// SECTION 3
$sqlDeleteDailyWin = $db->prepare("
    DELETE FROM daily_wins
    WHERE user_id = :userId AND id = :winId
");
try {
    $sqlDeleteDailyWin->execute([
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



