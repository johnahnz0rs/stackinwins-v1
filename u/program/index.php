<?php

session_start();
date_default_timezone_set('America/Los_Angeles');

/*
WHAT THE DASHBOARD DOES
1. "authenticate"
2. requires & vars

- Section 1 - validate user login; if not logged in, sign them out (the signout flow will take care of them)
- Section 2 - gather static variables
- Section 3 - grab all of user's wins for today ($todayWins); grab all of user's daily_wins ($dailyWins)
- Section 4 - check if todayWins has every id from dailyWins; add new dailyWins as required
*/




/* SECTION 1 - check for login */
if ( !isset($_COOKIE['user_id']) or !isset($_COOKIE['username']) ) {
    $headerString = 'Location: /u/signout';
    header($headerString);
}


/* SECTION 2 - requires & vars */
require '../app/db.php';
// user info
$userId = $_COOKIE['user_id'];
$username = $_COOKIE['username'];
// ui
$dateToday = isset($_GET['date']) ? strval($_GET['date']) : strval(date('Y-m-d'));
$dateYesterday = strval( date('Y-m-d', (strtotime('-1 day', strtotime( $dateToday )) )) );
$dateTomorrow = strval( date('Y-m-d', ( strtotime('+1 day', strtotime( $dateToday )) )) );
setcookie('date_today', $dateToday, time()+120, '/');
$stackCount = 0;
// user's daily wins
$dailyWins = [];
$dailyWinsIds = [];
$addTheseDailyWinsToTodayWins = [];
// today's wins
$todayWins = [];
$todayWinsIds = [];
$todayWinsJson = '';


/* SECTION 3 - get user's dailyWins/id's & get user's todayWins/id's */
// get user's dailyWins
$sqlDailyWins = $db->prepare("SELECT id, win FROM daily_wins WHERE user_id = :userId");
try {
    $sqlDailyWins->execute([
        'userId' => $userId
    ]);
} catch (PDOException $e) {
    $output .= $e->getMessage();
    echo $output;
    die();
}
$dailyWins = $sqlDailyWins->rowCount() ? $sqlDailyWins->fetchAll(PDO::FETCH_ASSOC) : null;
// get user's dailyWinsIds
if (count($dailyWins) > 0) {
    foreach ($dailyWins as $win) {
        $dailyWinsIds[] = $win['id'];
    }
}
// get user's todayWins
$sqlTodayWins = $db->prepare("SELECT id, win_id, win, note, stacked FROM wins WHERE user_id = :userId AND date = :dateToday");
try {
    $sqlTodayWins->execute([
        'userId' => $userId,
        'dateToday' => $dateToday
    ]);
} catch (PDOException $e) {
    $output .= $e->getMessage();
    echo $output;
    die();
}
$todayWins = $sqlTodayWins->rowCount() ? $sqlTodayWins->fetchAll(PDO::FETCH_ASSOC) : null;
// get user's todayWinsIds
if (count($todayWins) > 0) {
    foreach($todayWins as $win) {
        $todayWinsIds[] = $win['win_id'];
        if ($win['stacked'] == 1) {
            $stackCount += 1;
        }
    }
    $todayWinsJson = json_encode($todayWins);
}


/* SECTION 4 - compare user's dailyWins & todayWins */
if( !$dailyWins ) {
    // if user doesn't have any dailyWins, then add these (hardcoded 2020-08-29)
    $headerString = 'Location: ../app/add-default-daily-wins-to-user.php';
    header($headerString);
} else {
    foreach($dailyWins as $win) {
        if (!in_array($win['id'], $todayWinsIds)) {
            $addTheseDailyWinsToTodayWins[] = $win;
        }
    }
}

if (count($addTheseDailyWinsToTodayWins) > 0) {
    // pass wins assoc_array to cookie
    $cookieAddWinsToToday = json_encode($addTheseDailyWinsToTodayWins);
    setcookie('add_wins_to_today', $cookieAddWinsToToday, time()+300, '/');
    // redirect to script
    $headerString = 'Location: ../app/add-daily-wins-to-today.php';
    header($headerString);
}


include_once '../components/header.php';
?>



<div id="page-dash" class="stackin-page py-3 px-5 stackin-fullscreen-row">

    <!-- dates -->
    <div id="date-wins" class="py-3 text-center">
        <a class="mr-4 align-middle d-inline" href="/u/dash?date=<?php echo (string) $dateYesterday; ?>"><i class="fas fa-chevron-circle-left fa-lg"></i></a>
        <a class="todo-select-date font-big-john align-middle" href="dash.php?date=<?php echo (string) $dateToday; ?>"><h2 class="d-inline"><?php echo (string) $dateToday; ?></h2></a>
        <a class="ml-4 align-middle d-inline" href="/u/dash?date=<?php echo (string) $dateTomorrow; ?>"><i class="fas fa-chevron-circle-right fa-lg"></i></a>
    </div>
    <!-- END dates -->

    <!-- wins count -->
    <div id="wins-count" class="col-12 col-md-6 mx-auto">
        <h3 class="text-center">
            <span class="font-slim-joe p-1 pt-2" style="background-color: black; color: white;"><span id="stacked-count"></span> wins</span><span class="font-big-john pt-2 px-1" style="border: 1px solid black; font-size: 1.05em;">STACKED </span>
        </h3>
        <!-- javascript will add the wins -->
    </div>
    <!-- end wins -->


    <?php // include_once '../dash/u-dash-whatdo.php'; ?>


    <!-- form: add another win to today only -->
    <p id="form-wins-collapse-link" class="text-center font-slim-joe">
        <a class="" data-toggle="collapse" href="#form-wins" role="button" aria-expanded="false" aria-controls="collapseExample">
            Add another win for today
        </a>
    </p>
    <div id="form-wins" class="collapse pb-3 form-wins">
        <form id="form-new-win" action="../app/add-new-win.php" method="POST" class="col-12 col-md-6 mx-auto mb-5">
            <textarea name="win" id="win-text-area" placeholder="Add another variable to today only" rows="2" style="overflow: auto;"></textarea>

            <!-- hidden values -->
            <input type="hidden" name="user_id" id="" value="<?php echo (string) $userId; ?>">
            <input type="hidden" name="date" id="" value="<?php echo (string) $dateToday; ?>">

            <input id="submit-new-win" class="btn btn-success" type="submit" value="Add">

            <p class="mt-2">
                <a class="font-slim-joe" style="font-size: .6em;" href="../my-wins">Click here to add a Win that repeats daily</a>
            </p>
        </form>
    </div>
    <!-- end form -->


    <div id="today-wins" class="col-12 col-sm-10 col-md-8 mx-auto">
    </div>


</div>






<script>
$(document).ready(function() {

    // set vars
    var todayWins = <?php echo $todayWinsJson; ?>;
    var stackCount = parseInt(<?php echo $stackCount; ?>);

    /* for each todayWin: add to the page */
    $.each(todayWins, function(index, value) {
        // create the codeblock for each todayWin
        var winString = '<p class="today-win" id="' + value['id'] + '">';

        // different checkbox depending on "stacked" status
        if (value['stacked'] == 1) {
            // already stacked
            winString += '<span class="win-checkmark mr-auto"><i class="far fa-check-square fa-lg win-stacked" onclick="unstackWin(' + value['id'] + ')"></i></span>';
        } else {
            // to be stacked
            winString += '<span class="win-checkmark mr-auto"><i class="far fa-square fa-lg win-not-stacked" onclick="stackWin(' + value['id'] + ')"></i></span>';
        }
        winString += '<span class="ml-3">' + value['win'] + '</span></p>';

        // add todayWin, stackCount
        $('#today-wins').append(winString);
        $('#stacked-count').text(stackCount);
    });

});

function stackWin(winId) {
    // set vars
    var targetElement = '#' + winId + ' .win-checkmark';
    var stackURL = '../app/stack-win.php?id=' + winId;
    var newCheckmark = '<i class="far fa-check-square fa-lg win-stacked" onclick="unstackWin(' + winId + ')"></i>';
    var stackCount = parseInt( $('#stack-count').text() );

    // call the update script and update .win-checkmark
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if( this.readyState == 4 && this.status == 200 ) {
            if (this.responseText == 1) {
                $(targetElement).html(newCheckmark);
                stackCount += 1;
                $('#stack-count').text(stackCount);
            }
        }
    }
    xhttp.open('GET',stackURL);
    xhttp.send();
}

function unstackWin(winId) {
    // set vars
    var targetElement = '#' + winId + ' .win-checkmark';
    var unstackURL = '../app/unstack-win.php?id=' + winId;
    var newCheckmark = '<i class="far fa-square fa-lg win-not-stacked" onclick="stackWin(' + winId + ')"></i>';
    var stackCount = parseInt( $('#stack-count').text() );

    // call the update script and update .win-checkmark
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if( this.readyState == 4 && this.status == 200 ) {
            if (this.responseText == 1) {
                $(targetElement).html(newCheckmark);
                if (stackCount > 0) {
                    stackCount -= 1;
                }
                $('#stack-count').text(stackCount);
            }
        }
    }
    xhttp.open('GET',unstackURL);
    xhttp.send();
}

</script>




<?php include_once '../components/footer.php';
