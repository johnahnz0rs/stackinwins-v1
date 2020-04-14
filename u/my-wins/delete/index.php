<?php

/* What this page does:

- Section 1: validate user login; if no login, send to signout.
- Section 2: gather static variables
- Section 3: get the daily_win in question
- Section 4: confirm if user wants to delete this daily_win

*/

// SECTION 1
session_start();
if ( !isset($_COOKIE['user_id']) or !isset($_COOKIE['username']) or !isset($_GET['id']) ) {
    header('Location: /u/signout');
}


// SECTION 2
require '../../app/db.php';
$userId = strval($_COOKIE['user_id']);
$username = strval($_COOKIE['username']);
$winId = strval($_GET['id']);


// SECTION 3
$sqlGetWin = $db->prepare("
    SELECT win FROM daily_wins WHERE user_id = :userId and id = :winId
");
try {
    $sqlGetWin->execute([
        'userId' => $userId,
        'winId' => $winId
    ]);
} catch(PDOException $e) {
    $output .= $e->getMessage();
    echo $output;
}
$win = $sqlGetWin->rowCount() ? $sqlGetWin->fetch(PDO::FETCH_ASSOC) : null;

if (!$win) {
    header('Location: ../../u/signout');
}

require_once '../../components/header.php';
?>










<div id="page-my-wins-delete" class="stackin-page stackin-fullscreen-row">


    <div id="header-delete-win" class="font-big-john text-center mt-5 mb-4 pt-5 mx-auto">
        <h1>Are you sure</h1>
        <h2 style="">you want to Delete This Win?</h2>
    </div>

    <div class="col-12 col-md-4 mx-auto">

        <form id="form-delete-daily-win" action="../../app/delete-daily-win.php" method="POST">

            <p id="win"><?php echo (string) $win['win']; ?></p>

            <!-- hidden values -->
            <input id="user-id" name="user-id" type="hidden" value="<?php echo (string) $userId; ?>">
            <input id="win-id" name="win-id" type="hidden" value="<?php echo (string) $winId; ?>">

            <p class="mt-4 text-center">
                <a class="btn btn-lg btn-secondary font-slim-joe m-1" href="/u/my-wins">Cancel</a>
                <input class="btn btn-lg btn-warning font-big-john m-1" type="submit" role="button" value="Delete This Win">
            </p>
        </form>


    </div>






</div>







<?php
require_once '../../components/footer.php';