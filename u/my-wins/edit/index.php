<?php

/* What this page does:

- Section 1: validate user login; if no login, send to signout.
- Section 2: gather static variables
- Section 3: get the win in question
- Section 4: display a form, method post, action ...

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

// var_dump($win);
// echo 'lol ' . $userId . ', ' . $username . ', ' . $winId;

require_once '../../components/header.php';
?>










<div id="page-my-wins-edit" class="stackin-page stackin-fullscreen-row">
    <h1 class="font-big-john text-center mt-5 pt-5">Edit My Win</h1>

    <div class="col-12 col-md-4 mx-auto">

        <form id="form-update-daily-win" action="../../app/update-daily-win.php" method="POST">

            <textarea id="win" name="win" rows="3"><?php echo (string) $win['win']; ?></textarea>

            <!-- hidden values -->
            <input id="user-id" name="user-id" type="hidden" value="<?php echo (string) $userId; ?>">
            <input id="win-id" name="win-id" type="hidden" value="<?php echo (string) $winId; ?>">

            <p class="mt-4 text-center">
                <a class="btn btn-lg btn-secondary font-slim-joe m-1" href="/u/my-wins">Cancel</a>
                <input class="btn btn-lg btn-warning font-big-john m-1" type="submit" role="button" value="Update My Win">
            </p>
        </form>


    </div>






</div>






<script>
$(document).ready(function() {

    var newWinsCount = 0;
    // console.log('newWinsCount: ' + newWinsCount);

    $('#add-new-daily p').click(function() {
        var newElement = '<input id="new-' + newWinsCount.toString() + '" class="daily-win new-new" type="text" value="New Daily Win">';
        $('#the-wins').append(newElement);
        newWinsCount += 1;
        $('#new-wins-count').val(newWinsCount);
        // console.log('newWinsCount at the end: ' + newWinsCount);
        // console.log('newElement: ' + newElement);
    });

});


</script>

<?php
require_once '../../components/footer.php';