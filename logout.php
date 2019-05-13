<?php

session_start();

// remove all session variables
session_unset(); 
// destroy the session 
session_destroy();



$home_url = 'https://www.sblwilliams.com/hollandale/login.php'; 
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: application/xml; charset=utf-8");
header('Location: ' . $home_url);

?>