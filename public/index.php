<?php

define("ROOT", dirname(__DIR__));

require ROOT . '/vendor/autoload.php';

use App\classes\DB;

$dbHost = 'mysql';
$dbUser = 'root';
$dbName = 'billpay';
$dbPassword = 'rootpassword';
$dbConnection = new DB($dbHost, $dbUser, $dbName, $dbPassword);
