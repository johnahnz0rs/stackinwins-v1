<?php

// get username and pass $_POST
// check the pair
// -- if bad match, then send back to login-form w/ err-msg
// -- if good, then set the date param, get user's daily wins and fwd to dash

session_start();
require '../db.php';
date_default_timezone_set('America/Los_Angeles');

// check for username
$username = $_POST['username'];
$password = $_POST['password'];
$sqlUser = $db->prepare("SELECT id, pass_hash FROM users WHERE username = :username");
try {
    $sqlUser->execute(['username' => $username]);
} catch (PDOException $e) {
    $output .= $e->getMessage();
    echo $output;
}
$user = $sqlUser->rowCount() ? $sqlUser->fetch(PDO::FETCH_ASSOC) : null;

// no username
if ( !$user ) {
    setcookie('err_no_such_user', 'Username not found; please try again.', time()+7, '/');
    header('Location: /login');
// yes username; now check pass
} else {
    // bad pass
    if ( !password_verify($password, $user['pass_hash']) ) {
        setcookie('err_bad_pass', 'Incorrect password; please try again', time()+7, '/');
        header('Location: /login');
    // good pass
    // good pass
    // good pass
    // good pass
    // good pass
    // good pass
    // good pass
    } else {
        /* set cookies (user_id, username) */
        $userId = $user['id'];
        setcookie('user_id', $userId, time()+60*60*24, '/');
        setcookie('username', $username, time()+60*60*24, '/');
        usleep(500000);

        /* set date and fwd to dashboard */
        $dateToday = date( 'Y-m-d' );
        $headerString = 'Location: /u/dash?date=' . $dateToday;
        header($headerString);
    }
}

