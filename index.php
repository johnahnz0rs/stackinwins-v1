<?php

session_start();

// check if user is logged in & not null
if ( isset($_COOKIE['user_id']) and isset($_COOKIE['username']) and (strlen($_COOKIE['user_id'])>0) and (strlen($_COOKIE['username'])>0) ) {
    header('Location: /u/dash');
} else {
    require_once 'components/header-nouser.php';
}



?>


<div id="page-index" class="stackin-page">

    <?php
    if( isset($_COOKIE['success_signout']) ) {
        echo '<div class="text-center bg-info text-white"><p>'.$_COOKIE['success_signout'].'</p></div>';
    }
    ?>

    <!-- homepage hero -->
    <?php include_once 'components/section-homepage-hero.php'; ?>
    <!-- end homepage hero -->


    <!-- about -->
    <?php include_once 'components/section-about.php'; ?>
    <!-- end about -->


    <!-- wes watson -->
    <?php //include_once 'components/section-wes-watson.php'; ?>
    <!-- end wes watson -->


    <!-- signup -->
    <?php include_once 'components/section-signup-form.php'; ?>
    <!-- end signup -->

</div>


<?php
require_once 'components/footer-nouser.php';