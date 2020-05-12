<?php

// Useful php.ini file settings:
// session.cookie_lifetime = 0
// session.cookie_secure = 1
// session.cookie_httponly = 1
// session.use_only_cookies = 1
// session.entropy_file = "/dev/urandom"
// Must have already called:
// session_start();
// Function to forcibly end the session
function end_session() {
    // Use both for compatibility with all browsers
    // and all versions of PHP.
    session_unset();
    session_destroy();
}

// Does the request IP match the stored value?
function request_ip_matches_session() {
    // return false if either value is not set
    if (!isset($_SESSION['ip']) || !isset($_SERVER['REMOTE_ADDR'])) {
        return false;
    }
    if ($_SESSION['ip'] === $_SERVER['REMOTE_ADDR']) {
        return true;
    } else {
        return false;
    }
}

// Does the request user agent match the stored value?
function request_user_agent_matches_session() {
    // return false if either value is not set
    if (!isset($_SESSION['user_agent']) || !isset($_SERVER['HTTP_USER_AGENT'])) {
        return false;
    }
    if ($_SESSION['user_agent'] === $_SERVER['HTTP_USER_AGENT']) {
        return true;
    } else {
        return false;
    }
}

// Has too much time passed since the last login?
function last_login_is_recent() {
    $max_elapsed = 60 * 60 * 24 * 7; // 1 week
    // return false if value is not set
    if (!isset($_SESSION['last_login'])) {
        return false;
    }
    if (($_SESSION['last_login'] + $max_elapsed) >= time()) {
        return true;
    } else {
        return false;
    }
}

// Should the session be considered valid?
function is_session_valid() {
    $check_ip = true;
    $check_user_agent = true;
    $check_last_login = true;

    if ($check_ip && !request_ip_matches_session()) {
        return false;
    }
    if ($check_user_agent && !request_user_agent_matches_session()) {
        return false;
    }
    if ($check_last_login && !last_login_is_recent()) {
        return false;
    }
    return true;
}

// If session is not valid, end and redirect to login page.
function confirm_session_is_valid() {
    if (!is_session_valid()) {
        end_session();
        // Note that header redirection requires output buffering 
        // to be turned on or requires nothing has been output 
        // (not even whitespace).
        header("Location: index.php");
        exit;
    }
}

// Is user logged in already?
function is_logged_in() {
    return (isset($_SESSION['last_login']) && $_SESSION['last_login']);
}

// If user is not logged in, end and redirect to login page.
function confirm_user_logged_in() {
    if (!is_logged_in()) {
        end_session();
        // Note that header redirection requires output buffering 
        // to be turned on or requires nothing has been output 
        // (not even whitespace).
        header("Location: index.php");
        exit;
    }
}

// Actions to preform after every successful login
function after_successful_login($username = NULL) {
    // Regenerate session ID to invalidate the old one.
    // Super important to prevent session hijacking/fixation.
    session_regenerate_id();
    $lifetime = 60 * 60 * 24 * 7;
    setcookie(session_name(), session_id(), time() + $lifetime);
    $_SESSION['username'] = $username;

    // Save these values in the session, even when checks aren't enabled 
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    $_SESSION['last_login'] = time();
}

// Actions to preform after every successful logout
function after_successful_logout() {
    $_SESSION['user_id'] = NULL;
    end_session();
}

// Actions to preform before giving access to any 
// access-restricted page.
function before_every_protected_page() {
    confirm_user_logged_in();
    confirm_session_is_valid();
}

