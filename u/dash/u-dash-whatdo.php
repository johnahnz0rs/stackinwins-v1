<?php






?>

<div id="section-whatdo-button">
    <!-- what should i do -->
    <a href="../whatdo" id="button-what-should-i-do" class="mx-auto my-5" role="button" style="display: block; padding: 5px 24px; text-align: center; background-color: gold; color: black; border: none; border-radius: 8px;">What Should I Do Now?</a>


    <!-- reveal the thing to do -->
    <div id="section-do-this" hidden>

        <!-- do this -->
        <div id="suggestion-do-this" class="my-3">
        </div>

        <!-- next step buttons -->
        <div class="my-3">
            <button id="button-lets-do-it" role="button">OK, Let's Do It!</button>
            <button id="button-something-else" role="button">Nah, something else.</a>
        </div>

        <!-- optional: help -->
        <div class="my-3">
            <a href="#" id="cta-help-me-with-this"><em style="font-size: 0.85em;">Help me with this</em></a>
        </div>
    </div>


</div>
<!-- 
<script>

$(document).ready(function() {

    // set vars
    var whatDoCandidates = [];
    var todayWins = <?php echo $todayWinsJson; ?>;
    todayWins.forEach(win => {
        whatDoCandidates.push(win);
    });
    var doThis;
    var randomNum;

    function whatShouldIDoNow() {
        randomNum = Math.floor((Math.random() * whatDoCandidates.length));
        doThis = whatDoCandidates[randomNum];
        $('#suggestion-do-this').html('<h3>' + doThis.win + '</h3>');
        $('#section-do-this').removeAttr('hidden');
        $('#button-what-should-i-do').attr('hidden', true);
    }

    // What Should I Do Now?
    $('#button-what-should-i-do').click(function() {
        whatShouldIDoNow();
        console.log('whatShouldIDo() whatDoCandidates');
        console.log(whatDoCandidates);
        console.log('randomNum: ' + randomNum);
    });

    // OK, let's do it.
    $('#button-lets-do-it').click(function() {
        console.log(doThis);
        const letsDoItURL = "../app/whatdo-letsdoit.php";
        $.post(letsDoItURL,
        {
            userId: <?php echo $userId; ?>,
            winId: doThis.id,
            dailyWinId: doThis.win_id
        },
        function(data, status) {
            // alert("Data: " + data + "\nStatus: " + status);
            console.log(data);
        });
        // $.ajax(letsDoItURL, {
        //     method: "POST",
        //     data: {
        //         userId: <?php echo $userId; ?>,
        //         winId: doThis.id,
        //         dailyWinId: doThis.win_id
        //     }
        // });
        alert('Get after it.');

    });
    // $('#button-lets-do-it').click(function() {
    //     console.log('you clicked "lets do it".');
    //     var xhttp = new XMLHttpRequest();
    //     xhttp.onreadystatechange = function() {
    //         if ( this.readyState == 4 && this.status == 200 ) {
    //             if ( this.responseText == 1 ) {
    //                 // some code
    //             }
    //         }
    //     }
    //     // xhttp.open('GET', 'https://some-url');
    //     // xhttp.send();
    // });

    // Nah, something else.
    $buttonSomethingElse = $("#button-something-else");
    $buttonSomethingElse.click(function() {
        console.log('you clicked "nah something else".');
        // console.log('whatDoCandidates before: \n', whatDoCandidates);
        // const index = randomNum - 1;
        console.log(randomNum);
        whatDoCandidates.splice(randomNum, 1);
        whatShouldIDoNow();
        console.log('whatDoCandidates after: \n', whatDoCandidates);
        if (whatDoCandidates.length == 1) {
            $buttonSomethingElse.attr('disabled', true);
        }
    });

    $('#cta-help-me-with-this').click(function() {
        alert('More help coming soon. Jah bless.');
    })



});


/*
when user clicks "what should i do"
return a random pick from previously-defined:
*/

</script> -->