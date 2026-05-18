<?php


define('BASE_URL', 'http://localhost/mr_hop/');


define('APP_NAME', 'Mr. Hop - Cerveza Artesanal');


define('ROOT_PATH', dirname(__DIR__));


date_default_timezone_set('America/Mexico_City');


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


error_reporting(E_ALL);
ini_set('display_errors', 1);
