<?php
require_once 'assets/config/config.php';
$_SESSION['api_key'] = bin2hex(random_bytes(32)); // 64 characters long
?>
<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0, width=device-width" />
        <title>The Chalkboard Quiz</title>
        <link rel="stylesheet" type="text/css" href="assets/css/stylesheet.css">
        <link rel="stylesheet" type="text/css" href="assets/css/challenge_style.css">
        <script type="text/javascript"src="assets/js/game.js" defer></script>
    </head>
    <body>
        <noscript>
        <h1>Sorry, but you need Javascript enabled to use this website.</h2>
        </noscript>
        <div id="page">
            <section class="main">
                <p class="banner">The Chalkboard Quiz by <a class="website" href="https://www.miniaturephotographer.com/">The Miniature Photographer</a></p>
                <div id="header">
                    <div id="startBtn">
                        <a class="logo" id="customBtn" title="Start Button" href="index.php"><span>Start Button</span></a>
                    </div>
                    <form class="login" action="login.php" method="post">
                        <fieldset>
                            <legend>Login Form</legend>
                            <input type="hidden" name="action" value="44c5913657a376274ad05bc1291e0a811bd73e59a1e67b08eb9f96b6962a7b6b">
                            <label for="username">Username</label>
                            <input id="username" type="text" name="username" value="" tabindex="1" autofocus="">
                            <label for="password">Password</label>
                            <input id="password" type="password" name="password" tabindex="2">
                            <input type="submit" name="submit" value="enter" tabindex="3">
                        </fieldset>
                    </form>
                </div>
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
                <div id="triviaInfo">
                    <h2>Welcome to Chalkboard Quiz</h2>
                    <p>This is a JavaScript Quiz that is fun and educational. Many improvements will be taking place in the next couple of weeks, such as a high score table, user login, add/edit questions and many other new features will be added to the game. In the meantime try out the fully functional quiz game by pressing the START button.</p>
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
        </div>
</body>
</html>
