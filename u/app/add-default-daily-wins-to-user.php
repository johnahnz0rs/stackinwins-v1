<?php
session_start();
require './db.php';
$userId = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : null;
$dateToday = isset($_COOKIE['date_today']) ? $_COOKIE['date_today'] : null;
$defaultDailyWins = [
    "Early wake-up time",
    "Morning workout -- get your heart PUMPing to receive Positive Mental Attitude",
    "Track your macronutrients"
];

// user doesn't have any daily_wins, so add these default/minimum wins
if ( $userId and $dateToday ) {

    // add defaults to user's daily_wins table
    $stringAddDailyWins = "INSERT INTO daily_wins (user_id, win) VALUES";
    foreach($defaultDailyWins as $win) {
        $win = addslashes($win);
        $stringAddDailyWins .= " ({$userId}, '{$win}'),";
    }
    $stringAddDailyWins = rtrim($stringAddDailyWins, ',');
    $sqlAddDailyWins = $db->prepare($stringAddDailyWins);
    try {
        $sqlAddDailyWins->execute();
    } catch (PDOException $e) {
        $output .= $e->getMessage();
        echo $output;
        die();
    }

    // get user's dailyWins
    $stringGetDailyWins = "SELECT id, win FROM daily_wins where user_id = {$userId}";
    $sqlGetDailyWins = $db->prepare($stringGetDailyWins);
    try {
        $sqlGetDailyWins->execute();
    } catch(PDOException $e) {
        $output .= $e->getMessage();
        echo $output;
        die();
    }
    $dailyWins = $sqlGetDailyWins->rowCount() ? $sqlGetDailyWins->fetchAll(PDO::FETCH_ASSOC) : null;

    // add user's dailyWins (w/ win_id) to user's todayWins
    if($dailyWins) {
        // prep query
        $stringAddDailyWinsToTodayWins = "INSERT INTO wins (user_id, win_id, date, win, stacked) VALUES";
        foreach( $dailyWins as $win ) {
            $winWin = addslashes($win['win']);
            $stringAddDailyWinsToTodayWins .= " ({$userId}, {$win['id']}, '{$dateToday}', '{$winWin}', 0),";
        }
        $stringAddDailyWinsToTodayWins = rtrim($stringAddDailyWinsToTodayWins, ',');
        // sql
        $sqlAddDailyWinsToTodayWins = $db->prepare($stringAddDailyWinsToTodayWins);
        try {
            $sqlAddDailyWinsToTodayWins->execute();
        } catch(PDOException $e) {
            $output = $e->getMessage();
            echo $output;
            die();
        }
    }




    // redirect to today
    $headerString = 'Location: /u/dash?date=' . $dateToday;
    header($headerString);
    die();

    // echo "user's dailyWins have been added to todayWins";
    // die();
}

$headerString = 'Location: /u/signout';
header($headerString);
