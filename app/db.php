<?php

// set vars
$dbhost = "mysql.stackinwins.com";
$dbname = "stackinwins_com";
$dbuser = "stackin_dbz0rs";
$dbpass = "easy2remember";
// $dbuser = $_ENV['dbuser];
// $dbpass = $_ENV['dbpass];

/* open connection */
try {
    $dbstr = 'mysql:host=' . $dbhost . ';dbname=' . $dbname;
    $db = new PDO( $dbstr, $dbuser, $dbpass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION) );
} catch ( PDOException $e ) {
    $output .= $e->getMessage();
    echo $output;
}