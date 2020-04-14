<?php

$loginPage = '/login';
// $loginPage = 'https://stackinwins.com/login';

?>

<div id="section-signup" class="stackin-fullscreen-row">

    <h2 class="text-center mb-4">
        <span class="font-slim-joe">S t a r t</span><br>
        <span class="font-big-john"> STACKIN Your Wins </span><br>
        <span class="font-slim-joe">T o d a y</span>
    </h2>

    <!-- err msg -->
    <?php
    if( isset($_COOKIE['err_user_exists']) ) {
        echo '<div class="text-center bg-danger text-white"><p>'.$_COOKIE['err_user_exists'].'</p></div>';
    }
    ?>


    <div class="d-flex justify-content-center">
        <form class="" action="app/post/signup.php" method="POST">

            <div class="form-group">
                <input class="stackin-signup-input form-control" id="signup-fname" name="fname" type="text" placeholder="First name (optional)">
            </div>

            <div class="form-group">
                <input class="stackin-signup-input form-control" id="signup-lname" name="lname" type="text" placeholder="Last name (optional)">
            </div>

            <div class="form-group">
                <input class="stackin-signup-input form-control" id="signup-username" name="username" type="text" placeholder="Username" required>
            </div>

            <div class="form-group">
                <input class="stackin-signup-input form-control" id="signup-email" name="email" type="email" placeholder="Email" required>
            </div>

            <div class="form-group">
                <input class="stackin-signup-input form-control" id="signup-pw" name="password" type="password" placeholder="Password" required>
            </div>

            <button class="stackin-signup-input btn btn-success font-big-john" type="submit">
                Let's GROW!
                <!-- <span class="font-slim-joe">Let's</span> -->
                <!-- <span class="font-big-john"> GROW!</span> -->
            </button>

            <div class="text-center py-3">
                <a class="login-link" href="<?php echo $loginPage; ?>">
                    <small>Already have an account? Log in.</small>
                </a>
            <div>

        </form>
    </div>

</div>
