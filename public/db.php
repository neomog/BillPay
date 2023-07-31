<?php

use App\classes\DB;

$dbHost = 'mysql';
$dbUser = 'user';
$dbName = 'billpay';
$dbPassword = 'password';
$dbConnection = new DB($dbHost, $dbUser, $dbName, $dbPassword);
var_export($dbConnection);


