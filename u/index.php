<?php
session_start();
if ( !isset($_COOKIE['user_id']) or !isset($_COOKIE['username']) ) {
    header('Location: signout');
} else {
    header('Location: dash');
}