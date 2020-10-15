<?php

function validateUser() {
    if ( !isset($_COOKIE['user_id']) or !isset($_COOKIE['username']) ) {
        $headerString = 'Location: /u/signout';
        header($headerString);
    }
}
