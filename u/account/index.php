<?php
session_start();

// user "validation"
if ( !isset($_COOKIE['user_id']) or !isset($_COOKIE['username']) ) {
    header('Location: /u/signout');
}

// requires & vars
require '../app/db.php';
$userId = $_COOKIE['user_id'];
$username = $_COOKIE['username'];

// get this user's info:// username, email, password
$sqlUserInfo = $db->prepare("SELECT id, username, fname, lname, email FROM users WHERE id = :userId");
try {
    $sqlUserInfo->execute([
        'userId' => $userId
    ]);
} catch (PDOException $e) {
    $output .= $e->getMessage();
    echo $output;
}
$userInfo = $sqlUserInfo->rowCount() ? $sqlUserInfo->fetch(PDO::FETCH_ASSOC) : null;

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

// make password reset form & backend
// make username reset form & backend
// make email reset form & backend

require_once '../components/header.php';
?>

<style>
    .account-section {
        border: 1px solid black;
        border-radius: 8px;
        margin: 24px 16px;
        padding: 8px 4px;
    }
    .account-section ul li {
        list-style: none;
    }
    .daily-win {
        background-color: rgba(0,0,0,0.1);
        border-radius: 8px;
        padding: 8px;
        margin: 12px 4px;
    }
</style>

<div id="page-my-account" class="stackin-page stackin-fullscreen-row">


    <h1 class="font-big-john text-center">My Account</h1>

    <div class="account-section">
        <h2 class="font-slim-joe text-center">Account Info</h2>
        <?php if( $userInfo ) { ?>
            <ul>
                <li><strong>User ID</strong>: <?php echo $userInfo['id'] ?></li>
                <li><strong>Username</strong>: <?php echo $userInfo['username'] ?></li>
                <li><strong>First Name</strong>: <?php echo $userInfo['fname']; ?></li>
                <li><strong>Last Name</strong>: <?php echo $userInfo['lname']; ?></li>
                <li><strong>Email</strong>: <?php echo $userInfo['email'] ?></li>
            </ul>

        <?php } ?>
    </div>

    <div class="account-section">
        <h2 class="font-slim-joe text-center">My Daily Wins</h2>
        <p class="mx-5 text-center">
            <em>These are Wins you STACK every day to earn your confidence and self-love.</em>
        </p>

        <div class="px-3">
            <?php foreach( $dailyWins as $win ) {
                echo '<div id="' . $win['id'] . '" class="daily-win d-flex"><p class="mr-auto">' . $win['win'] . '</p><p><a href="edit?id=' . $win['id'] . '"><i class="fas fa-pencil-alt mr-2"></i></a><a href="delete?id=' . $win['id'] . '"><i class="fas fa-trash"></i></a></p></div>';
            } ?>
        </div>

        <div class="mb-5">
            <form id="form-add-new-daily-win" name="form-add-new-daily-win" action="../app/add-new-win.php" method="POST">

                <!-- new win -->
                <textarea id="input-add-new-daily-win" name="new-win" type="text" rows="2" placeholder="Add another win" style="width: 100%;"></textarea>

                <!-- button -->
                <p class="mt-2 text-center">
                    <input class="btn btn-warning font-slim-joe" type="submit" role="button" value="Add New Daily Win">
                </p>

            </form>
        </div>


    </div>











</div>

<?php
require_once '../components/footer.php';