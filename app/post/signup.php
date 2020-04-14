<?php

session_start();
require '../db.php';


$fname = isset($_POST['fname']) ? $_POST['fname'] : '';
$lname = isset($_POST['lname']) ? $_POST['lname'] : '';
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];


/* check if user exists */
$sql_user_check = "SELECT id from users WHERE username=:username";
$does_user_exist = $db->prepare($sql_user_check);
$does_user_exist->execute([
    'username' => $username
]);



// if the username already exists
if ($does_user_exist->rowCount()) {
    setcookie('err_user_exists', 'Username is in use; please choose another.', time()+7, '/');
    header('Location: /#section-signup');


// else if username is available
} else {
    // hash the password
    $pass_hash = password_hash($password, PASSWORD_DEFAULT);
    if ($pass_hash) {
        // open connection
        try {
            // new user
            $sql_signup = "INSERT INTO users (username, fname, lname, email, pass_hash) VALUES (:username, :fname, :lname, :email, :pass_hash)";
            $new_user = $db->prepare($sql_signup);
            $new_user->execute([
                'username' => $username,
                'fname' => $fname,
                'lname' => $lname,
                'email' => $email,
                'pass_hash' => $pass_hash
            ]);
            $userId = $db->lastInsertId();



            // successful - redirect to /login
            setcookie('success_signup', 'Registration Successful! Please log in.', time()+7, '/');
            $headerString = 'Location: /login';
            header($headerString);
        } catch ( PDOException $e) {
            /* DEV */
            // $output .= $e->getMessage();
            // echo $output;

            setcookie('err_user_exists', 'Sorry, something went wrong; please try again', time()+7, '/');
            $headerString = 'Location: /#section-signup';
            header($headerString);
        }
    }
}








?>