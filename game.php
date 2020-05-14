<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";
require_once 'loginFunctions.php';

confirm_user_logged_in();
is_session_valid();

//echo "<pre>" . print_r($_SESSION, 1) . "</pre>";

$_SESSION['api_key'] = bin2hex(random_bytes(32)); // 64 characters long
?>
<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0, width=device-width" />
        <title>The Chalkboard Quiz</title>
        <link rel="stylesheet" type="text/css" href="assets/css/stylesheet.css">

        <script type="text/javascript"src="assets/js/game.js" defer></script>
    </head>
    <body>
        <noscript>
        <h1>Sorry, but you need Javascript enabled to use this website.</h2>
        </noscript>

        <section class="main">
            <p class="banner">The Chalkboard Quiz by <a class="website" href="https://www.miniaturephotographer.com/">The Miniature Photographer</a></p>
            <?php if (isset($_SESSION['last_login']) && $_SESSION['last_login']) { ?>
                <a class="logout" title="Logout of Website" href="logout.php">Logout</a>
            <?php } ?>
            <div id="quiz">
                <form id="gameCat" action="game.php" method="post">
                    <select id="selectCat" class="select-css" name="category" tabindex="1">
                        <option value="photography">Photography</option>
                        <option value="movie">Movie</option>
                        <option value="space">Space</option>
                    </select>
                </form>
                <div id="gameTitle">
                    <h2 class="gameTitle">Trivia Game</h2>
                </div>
                <div class="triviaContainer" data-key="<?php echo $_SESSION['api_key']; ?>" data-records=" ">             
                    <div id="mainGame">
                        <div id="headerStyle" data-user="">
                            <h2>Time Left: <span id="clock"></span></h2>
                        </div>

                        <div id="triviaSection" data-correct="">
                            <div id="questionBox">
                                <h2 id="question">What is the Question?</h2>
                            </div>
                            <div id="buttonContainer"></div>
                        </div>

                        <div id="playerStats">
                            <h2 id="score">Score 0 Points</h2>
                            <h2 id="percent">100 percent</h2>
                        </div>
                        <div id="nextStyle">
                            <button id="next" class="nextBtn">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </section> <!-- End of Section -->
        <footer>
            &copy; The Miniature Photographer
            <div class="content">
                <a class="menuExit" title="The Miniature Photographer" href="https://www.miniaturephotographer.com/">The Miniature Photographer</a>
                <!--        <a title="Terms of Service" href="#">Terms of Service</a>-->
            </div>
            Dedicated to Mildred I. Pepp (my Mom) 10-29-1928 / 02-26-2017
        </footer>


</body>
</html>
