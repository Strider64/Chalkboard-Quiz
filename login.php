<?php
require_once '../private/initialize.php';

use Library\Users as Login;

$login = new Login;

$submit = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if (isset($submit) && $submit === 'enter') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $id = $login->read($username, $password);
}

include '../private/includes/header.inc.php';
?>
<script>

    function statusChangeCallback(response) {  // Called with the results from FB.getLoginStatus().
        console.log('statusChangeCallback');
        console.log(response);                   // The current login status of the person.
        if (response.status === 'connected') {   // Logged into your webpage and Facebook.
            testAPI();
        } else {                                 // Not logged into your webpage or we are unable to tell.
            document.getElementById('status').innerHTML = 'Please log ' +
                    'into this webpage.';
        }
    }


    function checkLoginState() {               // Called when a person is finished with the Login Button.
        FB.getLoginStatus(function (response) {   // See the onlogin handler
            statusChangeCallback(response);
        });
    }


    window.fbAsyncInit = function () {
        FB.init({
            appId: '555200288448021',
            cookie: true, // Enable cookies to allow the server to access the session.
            xfbml: true, // Parse social plugins on this webpage.
            version: 'v6.0'           // Use this Graph API version for this call.
        });


        FB.getLoginStatus(function (response) {   // Called after the JS SDK has been initialized.
            statusChangeCallback(response);        // Returns the login status.
        });
    };


    (function (d, s, id) {                      // Load the SDK asynchronously
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id))
            return;
        js = d.createElement(s);
        js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));


    function testAPI() {                      // Testing Graph API after login.  See statusChangeCallback() for when this call is made.
        console.log('Welcome!  Fetching your information.... ');
        FB.api('/me', function (response) {
            console.log('Successful login for: ' + response.name);
            document.getElementById('status').innerHTML =
                    'Thanks for logging in, ' + response.name + '!';
        });
    }

</script>
<section class="main">
    <aside>
        <form class="login" action="login.php" method="post">
            <fieldset>
                <legend>Login Form</legend>
                <input type="hidden" name="action" value="<?php echo $_SESSION['token']; ?>">
                <label for="username">Username</label>
                <input id="username" type="text" name="username" value="" tabindex="1" autofocus>
                <label for="password">Password</label>
                <input id="password" type="password" name="password" tabindex="2">
                <input type="submit" name="submit" value="enter" tabindex="3">
            </fieldset>
        </form>
    </aside>
    <aside>
        <fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
        </fb:login-button>

        <div id="status">
        </div>
        <div class="charity">
            <h2>Help My Cousin Eddie</h2>
            <p>Hi everyone, I had Rob set this account up because I didn't know how to do it. This is Eddie in case you think this could be a scam, it's not. I don't like to ask for help but I'm in need of some now. I hope you can find it in your heart to help me out. I have spent 15 years caring for my brother Jim. Managed without any assistance until now. I need to pay back taxes on Jim's. Otherwise I will lose the property in foreclosure to Wayne county. Please help if you can, I would greatly appreciate your support. Thank you in advance love, Eddie</p>
            <a class="menuExit btn2" title="GoFundMe Info" href="https://bit.ly/2S3jkgJ">GoFundMe Page</a>
        </div>
    </aside>
</section>

<?php
include '../private/includes/footer.inc.php';
