<?php
session_start();
require('./db.php');
$userId = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : null;
$dateToday = isset($_COOKIE['date_today']) ? $_COOKIE['date_today'] : null;
$addWinsToToday = isset($_COOKIE['add_wins_to_today']) ? json_decode($_COOKIE['add_wins_to_today']) : null;

// echo '<a href="/u/dash">Dashboard</a><br>';
// echo 'this is add-daily-wins-to-today.php<br>';

if ($userId and $dateToday and $addWinsToToday) {
    // echo 'this is the if loop<br>';
    // die();

    $stringAddWinsToToday = "INSERT INTO wins (user_id, win_id, win, date, stacked) VALUES";
    // append wins to string
    foreach( $addWinsToToday as $win ) {
        $win = (array) $win;
        $winWin = addslashes($win['win']);
        $stringAddWinsToToday .= " ({$userId}, {$win['id']}, '{$winWin}', '{$dateToday}', 0),";
    }
    $stringAddWinsToToday = rtrim($stringAddWinsToToday, ',');
    // var_dump($stringAddWinsToToday);
    // die();

    $sqlAddWins = $db->prepare($stringAddWinsToToday);
    try {
        $sqlAddWins->execute();
    } catch(PDOException $e) {
        $output = $e->getMessage();
        echo $output;
        die();
    }
    // echo 'ok sent the insert mysql command';
    // die();
}

// echo 'exited the if loop<br>';
$headerString = 'Location: ../dash?date=' . $dateToday;
setcookie('date_today', '', -3600, '/');
setcookie('add_wins_to_today', '', -3600, '/');
header($headerString);
