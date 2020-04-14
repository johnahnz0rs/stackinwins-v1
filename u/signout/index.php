<?php
session_start();

// clear the session
setcookie('user_id', '', -3600, '/');
setcookie('username', '', -3600, '/');
setcookie('success_signout', 'Signout successful. Have a great day!', 10, '/');
usleep(500000);
header('Location: /');

