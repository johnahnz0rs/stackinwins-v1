<?php
session_start();
if( !isset($_COOKIE['user_id']) or !isset($_COOKIE['username']) ) {
    $headerString = 'Location: /u/signout';
    header($headerString);
}

require_once '../components/header.php';

?>



<div id="page-faq" class="stackin-page stackin-fullscreen-row text-center">
    <h1 class="font-big-john mt-5 pt-5">FAQ</h1>
    <h2 class="font-slim-joe">Coming Soon</h2>
    <div class="mt-5">
        <a href="/u/dash">Back to Dashboard</a>
    </div>


</div>



<?php
require_once '../components/footer.php';