<?php
$submit = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if (isset($submit) && $submit === 'enter') {
    $data = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
}
?>
<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0, width=device-width" />
        <title>The Chalkboard Quiz</title>
        <link rel="stylesheet" type="text/css" href="assets/css/stylesheet.css">
    </head>
    <body>
        <section class="main">
            <form id="register" class="span7" action="" method="post" autocomplete="on">
                <fieldset>
                    <legend></legend>
                    <label for="username">username</label>
                    <input id="username" type="text" name="data[username]" value="">
                    <label for="password">password</label>
                    <input id="password" type="password" name="data[password]">
                    <label for="email">email</label>
                    <input id="email" type="text" name="data[email]" value="">

                    <input id="submit" type="submit" name="submit" value="enter">

                </fieldset>
            </form>
        </section>
    </body>
</html>
