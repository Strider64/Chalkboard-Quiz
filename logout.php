<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";
require_once 'loginFunctions.php';

end_session();
header("Location: index.php");
exit();