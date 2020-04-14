<?php

/*
WHAT THIS PAGE DOES

- Section 1 - validate user login; if not logged in, sign them out (the signout flow will take care of them)
- Section 2 - gather static variables
- Section 3 - grab all of user's wins for today ($todayWins); grab all of user's daily_wins ($dailyWins)
- Section 4 - check if todayWins has every id from dailyWins; add new dailyWins as required
*/











// default daily wins
// $dailyWin1 = 'Make your bed — https://youtu.be/3sK3wJAxGfs';
// $dailyWin2 = 'Brush your teeth, brush your hair, wear clean clothes — have some SELF-respect';
// $dailyWin3 = 'EARN your PMA positive mental attitude. You can ONLY feel good about yourself and your life if you\'ve DONE something to feel good about! A negative mind can ONLY see the roadblocks, and never the solutions. So EARN your PMA by doing a BUNCH of things that make you feel good, feel proud.';

// $stringDefaultWins = "INSERT INTO daily_wins (user_id, win) VALUES (:userId, :win1), (:userId, :win2), (:userId, :win3)";
// $sqlDailyWins = $db->prepare($stringDefaultWins);
// try {
//     $sqlDailyWins->execute([
//         'userId' => $userId,
//         'win1' => $dailyWin1,
//         'win2' => $dailyWin2,
//         'win3' => $dailyWin3
//     ]);
// } catch (PDOException $e) {
//     /* DEV */
//     $output .= $e->getMessage();
//     echo $output;
// }





















session_start();
date_default_timezone_set('America/Los_Angeles');


/* SECTION 1 */
// if not logged in: send to signout.
if ( !isset($_COOKIE['user_id']) or !isset($_COOKIE['username']) ) {
    header('Location: /u/signout');
}



/* SECTION 2 */
// if logged in: get user info & dates
require '../app/db.php';
$userId = $_COOKIE['user_id'];
$username = $_COOKIE['username'];
$dateToday = isset($_GET['date']) ? strval($_GET['date']) : strval(date('Y-m-d'));
$dateYesterday = strval( date('Y-m-d', ( strtotime('-1 day', strtotime( $dateToday )) )) );
$dateTomorrow = strval( date('Y-m-d', ( strtotime('+1 day', strtotime( $dateToday )) )) );
$todayWins; // this array will contain all of user's wins for today
$todayWinsJson;
$eachTodayWinId = []; // this array will contain the id of each of today's win; used to check if user's daily_wins exist in todayWins
$dailyWins = []; // this array will contain all of user's daily_wins, to check if each daily_win exists in todayWins.
$stackCount = 0; // this will display how many of today's wins have been STACKED




/* SECTION 3 */
// i wanna see the win_ids to make sure each daily_win is in wins

// $todayWins = all of user's wins (w/ date = today)
$sqlTodayWins = $db->prepare("SELECT id, win_id, win, note, stacked FROM wins WHERE user_id = :userId AND date = :dateToday");
try {
    $sqlTodayWins->execute([
        'userId' => $userId,
        'dateToday' => $dateToday
    ]);
} catch (PDOException $e) {
    $output .= $e->getMessage();
    echo $output;
}
$todayWins = $sqlTodayWins->rowCount() ? $sqlTodayWins->fetchAll(PDO::FETCH_ASSOC) : null;


// $eachTodayWinId = win_id of each item in $todayWins
if ($todayWins) {
    foreach($todayWins as $todayWin) {
        if ( $todayWin['win_id'] and !in_array($todayWin['win_id'], $eachTodayWinId) ) {
            $eachTodayWinId[] = $todayWin['win_id'];
        }
        if ($todayWin['stacked'] == 1) {
            $stackCount += 1;
        }
    }
    $todayWinsJson = json_encode($todayWins);
    // echo json_last_error_msg();
    die();
}

// $dailyWins = all of user's daily_wins
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
// if no daily wins, then add new daily wins
if (!$dailyWins) {

    // add default daily wins
    $dailyWin1 = 'Make your bed - youtu.be/3sK3wJAxGfs';
    $dailyWin2 = 'Brush your teeth, brush your hair, wear clean clothes -- have some SELF-respect';
    $dailyWin3 = "EARN your PMA positive mental attitude. You can ONLY feel good about yourself and your life if you've DONE something to feel good about! A negative mind can ONLY see the roadblocks, and never the solutions. So EARN your PMA by doing a BUNCH of things that make you feel good, feel proud.";

    $sqlAddDefaultDailyWins = $db->prepare("INSERT INTO daily_wins (user_id, win) VALUES (:userId, :win1), (:userId, :win2), (:userId, :win3)");
    try {
        $sqlAddDefaultDailyWins->execute([
            'userId' => $userId,
            'win1' => $dailyWin1,
            'win2' => $dailyWin2,
            'win3' => $dailyWin3
        ]);
    } catch (PDOException $e) {
        $output .= $e->getMessage();
        echo $output;
        die();
    }
    $headerString = 'Location: /u/dash?date=' . $dateToday;
    header($headerString);

}


/* SECTION 4 */
// check if each daily_win is in $todayWins; add to $addThese if not
$addThese = [];
foreach($dailyWins as $dailyWin) {
    if ( !in_array($dailyWin['id'], $eachTodayWinId) ) {
        $addThese[] = $dailyWin;
    }
}
// add dailyWins as required
$stringNewDailyWins = '';
if ( count($addThese) ) {
    $stringNewDailyWins .= "INSERT INTO wins (user_id, win_id, date, win) VALUES ";

    foreach($addThese as $newWin) {
        $stringNewDailyWins .= '(' . $userId . ', ' . $newWin['id'] . ', "' . strval($dateToday) . '", "' . $newWin['win'] . '"),';
    }
    $stringNewDailyWins = rtrim($stringNewDailyWins, ',');
}
if ( strlen($stringNewDailyWins) ) {
    $sqlAddNewDailyWins = $db->prepare($stringNewDailyWins);
    try {
        $sqlAddNewDailyWins->execute([
            'userId' => $userId,
            'dateToday' => $dateToday
        ]);
    } catch (PDOException $e) {
        $output .= $e->getMessage();
        echo $output;
        die();
    }
    $headerString = 'Location: /u/dash?date=' . $dateToday;
    header($headerString);
}

require_once '../components/header.php';

// var_dump($todayWins);
// var_dump($todayWins);
// var_dump($todayWins);
// var_dump($todayWins);
// var_dump($todayWins);
// var_dump($todayWins);
// foreach($todayWins as $win) {
//     echo '<pre>';
//     var_dump($win);
//     echo '</pre>';
// }
var_dump($todayWinsJson);
echo '<p style="width: 100%; height: 300px; background-color: black;">lol</p>';
var_dump($todayWins);
die();
?>















<div id="page-dash" class="stackin-page py-3 px-5 stackin-fullscreen-row">
    <!-- <div>
        <p>dev echo's</p>
    <?php
        // echo '$todayWins: ';
        // var_dump($todayWins);
        // echo '<br>';
    ?>
    </div> -->

    <!-- dates -->
    <div id="date-wins" class="py-3 text-center">
        <a class="mr-4 align-middle d-inline" href="/u/dash?date=<?php echo (string) $dateYesterday; ?>"><i class="fas fa-chevron-circle-left fa-lg"></i></a>
        <a class="todo-select-date font-big-john align-middle" href="dash.php?date=<?php echo (string) $dateToday; ?>"><h2 class="d-inline"><?php echo (string) $dateToday; ?></h2></a>
        <a class="ml-4 align-middle d-inline" href="/u/dash?date=<?php echo (string) $dateTomorrow; ?>"><i class="fas fa-chevron-circle-right fa-lg"></i></a>
    </div>
    <!-- END dates -->


    <!-- form -->
    <p id="form-wins-collapse-link" class="text-center font-slim-joe">
        <a class="" data-toggle="collapse" href="#form-wins" role="button" aria-expanded="false" aria-controls="collapseExample">
            Add another win for today
        </a>
    </p>
    <div id="form-wins" class="collapse pb-3 form-wins">
        <form id="form-new-win" action="../app/add-new-win.php" method="POST" class="col-12 col-md-4 mx-auto mb-5">
            <textarea name="win" id="win-text-area" placeholder="Add another variable to your day" rows="2" style="overflow: auto;"></textarea>

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


    <!-- wins -->
    <div id="today-wins" class="col-12 col-md-4 mx-auto">
        <h3 class="text-center">
            <span class="font-slim-joe p-1 pt-2" style="background-color: black; color: white;">
                <span id="stack-count"></span> wins</span><span class="font-big-john pt-2 px-1" style="border: 1px solid black; font-size: 1.05em;">STACKED </span></h3>
        <!-- javascript will add the wins -->
    </div>
    <!-- end wins -->


</div>






<script>
$(document).ready(function() {

    // $('#abc span').text('baa baa black sheep');

    /* get todayWins from php/mysql result */
    // var todayWins = <?php // echo json_encode($todayWins); ?>;
    var todayWins = <?php echo $todayWinsJson; ?>;
    console.log(todayWins);
    var stackCount = parseInt(<?php echo $stackCount; ?>);

    /* add each todayWin to the page */
    $.each(todayWins, function(index, value) {
        // console.log(index, ': ', value);

        // create the codeblock for each todayWin
        var winString = '<p class="today-win" id="' + value['id'] + '">';
        // different checkbox depending on status.
        if (value['stacked'] == 1) {
            // already stacked
            winString += '<span class="win-checkmark mr-auto"><i class="far fa-check-square fa-lg win-stacked" onclick="unstackWin(' + value['id'] + ')"></i></span>';
        } else {
            // to be stacked
            winString += '<span class="win-checkmark mr-auto"><i class="far fa-square fa-lg win-not-stacked" onclick="stackWin(' + value['id'] + ')"></i></span>';
        }
        winString += '<span class="ml-3">' + value['win'] + '</span>';
        winString += '</p>';
        $('#today-wins').append(winString);
        $('#stack-count').text(stackCount);

        /*
        we can add the edit and delete buttons/func's later on;
        for v1, let's just focus on getting the done, undone fixed.
        */
        // winString += '<span class="ml-auto"><i class="far fa-edit win-edit"></i><i class="far fa-trash-alt win-delete"></i></span>';
    });





    /* javascript to ajax-add-new-win */
    /* watch #form-new-win #content-new-win */

    // var newWin = {
    //     'user_id': <?php // echo $userId; ?>,
    //     'date': <?php // echo $dateToday; ?>,
    //     'content': ''
    // };
    // $('#content-new-win').keyup(function(e) {
    //     newWin['content'] = $.trim(e.target.value);
    //     // var echo = new URLSearchParams(newWin).toString();
    //     // var queryString = Object.keys(newWin).map(key => key + '=' + newWin[key]).join('&');
    //     // console.log(queryString);

    //     // console.log(queryString);
    //     newWin = JSON.stringify(newWin);
    //     console.log(newWin);
    // });



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









<?php
require_once '../components/footer.php';