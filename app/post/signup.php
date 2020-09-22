<?php

session_start();

// validate
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /#section-signup');
}

// requires and vars
require '../db.php';
$fname = isset($_POST['fname']) ? $_POST['fname'] : '';
$lname = isset($_POST['lname']) ? $_POST['lname'] : '';
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];


// check if user exists
$stringUserCheck = "SELECT id FROM users WHERE username = '{$username}'";
$sqlUserCheck = $db->prepare($stringUserCheck);
try {
    $sqlUserCheck->execute();
} catch(PDOException $e) {
    echo 'Exception: ', $e;
    die();
}
$doesUserExist = $sqlUserCheck->rowCount() ? true : false;


// if the username already exists, then try again (send back to signup-form)
if ($doesUserExist) {
    setcookie('err_user_exists', 'Username is in use; please choose another.', time()+10, '/');
    $headerString = 'Location: /#section-signup';
    header($headerString);
}


// hash the pass & insert
$passHash = password_hash($password, PASSWORD_DEFAULT);
$stringAddNewUser = "INSERT INTO users (username, fname, lname, email, pass_hash) VALUES ('{$username}', '{$fname}', '{$lname}', '{$email}', '{$passHash}')";
$sqlAddNewUser = $db->prepare($stringAddNewUser);
try {
    $sqlAddNewUser->execute();
} catch(PDOException $e) {
    echo 'Exception: ', $e;
    die();
}


// check if insert was successful and redirect as appropriate.
$userId = $db->lastInsertId() ? $db->lastInsertId() : null;

if ($userId) {
    setcookie('success_signup', 'Registration Successful! Please log in.', time()+10, '/');
    $headerString = 'Location: /login';
    header($headerString);
    die();
}
setcookie('err_user_exists', 'Sorry, something went wrong; please try again', time()+10, '/');
$headerString = 'Location: /#section-signup';
header($headerString);


?>