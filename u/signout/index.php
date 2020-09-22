<?php
session_start();

// clear the session
setcookie('user_id', '', -3600, '/');
setcookie('username', '', -3600, '/');
setcookie('date_today', '', -3600, '/');
setcookie('add_wins_to_today', '', -3600, '/');
setcookie('success_signout', 'Signout successful. Have a great day!', 10, '/');

// redirect
$headerString = 'Location: /';
header($headerString);
