<?php

session_start();
if ( !isset($_COOKIE['user_id']) or !isset($_COOKIE['username']) ) {
    header('Location: /u/signout');
}

$userId = $_COOKIE['user_id'];
$username = $_COOKIE['username'];


// get this user's info:
// username, email, password


// make password reset form & backend
// make username reset form & backend
// make email reset form & backend

require_once '../components/header.php';
?>

<div id="page-my-account" class="stackin-page stackin-fullscreen-row text-center">
    <h1 class="font-big-john mt-5 pt-5">My Account</h1>
    <h2 class="font-slim-joe">Coming Soon</h2>
</div>

<?php
require_once '../components/footer.php';