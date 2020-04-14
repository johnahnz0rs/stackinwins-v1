<?php
session_start();

// validate
if ( !isset($_COOKIE['user_id']) or !isset($_GET['id']) ) {
    header('Location: ../signout');
}

// set vars
$userId = $_COOKIE['user_id'];
$winId = $_GET['id'];
include 'db.php';

// get the win & check user_id
$sqlGetWin = $db->prepare("SELECT user_id FROM wins WHERE id = :winId");
try {
    $sqlGetWin->execute([
        'winId' => $winId
    ]);
} catch (PDOException $e) {
    $output .= $e->getMessage();
    echo $output;
}
$win = $sqlGetWin->rowCount() ? $sqlGetWin->fetch(PDO::FETCH_ASSOC) : null;

if ( !$win ) {
    echo '0';
    die();
}

if ( $win['user_id'] == $userId ) {

    $sqlUnstackWin = $db->prepare("
        UPDATE wins
        SET stacked = 0
        WHERE id = :winId
    ");
    try {
        $sqlUnstackWin->execute([
            'winId' => $winId
        ]);
    } catch (PDOException $e) {
        $output .= $e->getMessage();
        echo $output;
        die();
    }
    echo '1';
    die();

} else {
    echo '0';
    die();
}