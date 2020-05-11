<?php
// **PREVENTING SESSION HIJACKING**

/* Turn on error reporting */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if (filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_URL) == "localhost") {
    error_reporting(-1); // -1 = on || 0 = off
} else {
    error_reporting(0); // -1 = on || 0 = off
    ini_set('session.cookie_lifetime', 0);
// Prevents javascript XSS attacks aimed to steal the session ID
    ini_set('session.cookie_httponly', 1);

// **PREVENTING SESSION FIXATION**
// Session ID cannot be passed through URLs
    ini_set('session.use_only_cookies', 1);

// Uses a secure connection (HTTPS) if possible
    ini_set('session.cookie_secure', 1);
}


session_start();


if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
date_default_timezone_set('America/Detroit');

if (filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_URL) == "localhost") {
    define('PRIVATE_KEY', '6LcR8OQUAAAAAF46RBPBAV5Wf7KV5odKZ2z28GnL');
    define('DATABASE_HOST', 'localhost');
    define('DATABASE_NAME', 'game');
    define('DATABASE_USERNAME', 'root');
    define('DATABASE_PASSWORD', 'sabr1964');
    define('DATABASE_TABLE', 'members');
} else {
    define('PRIVATE_KEY', '6LdXNpAUAAAAAPkPQ5L1YqfrLcqkrwZXh8m33-Jg');
    define('DATABASE_HOST', 'db2555.perfora.net');
    define('DATABASE_NAME', 'db336894285');
    define('DATABASE_USERNAME', 'dbo336894285');
    define('DATABASE_PASSWORD', 'Dpsimfm1964!');
    define('DATABASE_TABLE', 'members');
    
}

header("Content-Type: text/html; charset=utf-8");
header('X-Frame-Options: SAMEORIGIN'); // Prevent Clickjacking:
header('X-Content-Type-Options: nosniff');
header('x-xss-protection: 1; mode=block');
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
//header("content-security-policy: default-src 'self'; report-uri /csp_report_parser");
header('X-Permitted-Cross-Domain-Policies: master-only');

/* Get the current page */
$phpSelf = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL);
$path_parts = pathinfo($phpSelf);
$basename = $path_parts['basename']; // Use this variable for action='':
$pageName = $path_parts['filename'];


