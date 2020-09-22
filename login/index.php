<?php
session_start();
$userId = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : null;

    // echo 'you are at /login/index.php';
    // die();

// check if user is logged in & not null
if ( $userId ) {
    header('Location: ../u/dash');
} else {
    require_once '../components/header-nouser.php';
}


?>

<div id="page-login" class="stackin-page">
    <div id="section-login" class="stackin-fullscreen-row container-fluid p-3">

        <h1 class="text-center mb-4 font-big-john p-3">Log In</h1>


        <div>
        <?php
        if( isset($_COOKIE['success_signup']) ) {
            echo '<div class="text-center bg-success text-white"><p>'.$_COOKIE['success_signup'].'</p></div>';
        }
        if( isset($_COOKIE['err_no_such_user']) ) {
            echo '<div class="text-center bg-danger text-white"><p>'.$_COOKIE['err_no_such_user'].'</p></div>';
        }
        if( isset($_COOKIE['err_bad_pass']) ) {
            echo '<div class="text-center bg-danger text-white"><p>'.$_COOKIE['err_bad_pass'].'</p></div>';
        }
        ?>
        </div>
        <!-- form -->
        <div class="d-flex justify-content-center">
            <form id="login-form" action="../app/post/login.php" method="POST">
                <div class="form-group">
                    <input class="stackin-login-input form-control" id="login-username" name="username" type="text" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <input class="stackin-login-input form-control" id="login-pw" name="password" type="password" placeholder="Password" required>
                </div>
                <button class="stackin-login-input btn btn-success" type="submit">Let's GROW!</button>
            </form>
        </div>
        <!-- end form -->

        <!-- link to signup -->
        <div class="text-center mt-3 mb-5">
            <a class="text-center login-link" href="../#section-signup">
                <small>Need an account? Sign up.</small>
            </a>
        <div>

    </div>
</div>



<?php
// echo $_SERVER['SERVER_NAME'];

require_once '../components/footer-nouser.php';