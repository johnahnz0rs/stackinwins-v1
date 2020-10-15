<?php

session_start();

/*
- show the user's dailyWins
- name the page My Daily Program
*/


// validation
if ( !isset($_COOKIE['user_id']) or !isset($_COOKIE['username']) ) {
    header('Location: /u/signout');
}


// requires & vars
require '../app/db.php';
$userId = $_COOKIE['user_id'];
$username = $_COOKIE['username'];


// get this user's wins
$sqlDailyWins = $db->prepare("
    SELECT id, win FROM daily_wins WHERE user_id = :userId ORDER BY id
");
try {
    $sqlDailyWins->execute([
        'userId' => $userId
    ]);
} catch (PDOException $e) {
    $output .= $e->getMessage();
    echo $output;
}
$dailyWins = $sqlDailyWins->rowCount() ? $sqlDailyWins->fetchAll(PDO::FETCH_ASSOC) : null;
// $oldWinsCount = 0;

require_once '../components/header.php';
?>










<div id="page-my-wins" class="stackin-page stackin-fullscreen-row">


    <div class="col-12 col-md-4 mx-auto">

        <h1 class="font-big-john text-center mt-5 pt-5">Daily Program</h1>
        <p class="font-slim-joe text-center pt-1 pb-4 " style="font-size: .8em;">STACK these wins EVERY Day to keep your Self-Confidence high. Self-Love is only attained through Action.</p>



        <div class="mb-5">
            <form id="form-add-new-daily-win" name="form-add-new-daily-win" action="../app/add-new-win.php" method="POST">

                <!-- new win -->
                <textarea id="input-add-new-daily-win" name="new-win" type="text" rows="2" placeholder="Add another win"></textarea>

                <!-- button -->
                <p class="mt-2 text-center">
                    <input class="btn btn-warning font-slim-joe" type="submit" role="button" value="Add New Daily Win">
                </p>

            </form>
        </div>

        <div id="the-wins">
            <?php
            foreach($dailyWins as $dailyWin) {
                echo '<div id="' . $dailyWin['id'] . '" class="daily-win d-flex px-3"><p class="mr-auto">' . $dailyWin['win'] . '</p><p class="ml-auto"><a href="edit?id=' . $dailyWin['id'] . '"><i class="fas fa-pencil-alt mr-2"></i></a><a href="delete?id=' . $dailyWin['id'] . '"><i class="fas fa-trash"></i></a></p></div>';
            }
            ?>
        </div>






        <!-- ARTIFACTS: ORIGINAL ATTEMPT TO ADD MULTIPLE NEW WINS AT ONCE -->
        <!-- ARTIFACTS: ORIGINAL ATTEMPT TO ADD MULTIPLE NEW WINS AT ONCE -->
        <!-- ARTIFACTS: ORIGINAL ATTEMPT TO ADD MULTIPLE NEW WINS AT ONCE -->

        <!-- <div id="add-new-daily" class="font-slim-joe my-3 d-flex">
            <p class="ml-auto text">Add a new Daily Win</p>
        </div> -->

        <!-- <input id="user-id" name="user-id" type="hidden" value="<?php // echo (string) $userId; ?>"> -->

        <!-- <p class="mt-4">
            <a class="btn btn-lg btn-secondary font-slim-joe m-1" href="/u/dash">Cancel</a>
            <input class="btn btn-lg btn-warning font-big-john m-1" type="submit" role="button" value="Update My Wins">
        </p> -->


    </div>

</div>





<!--
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


</script> -->

<?php
require_once '../components/footer.php';