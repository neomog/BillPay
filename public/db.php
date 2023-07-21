<?php

use App\classes\DB;
$dbHost = 'db';
$dbUser = 'user';
$dbName = 'billpay';
$dbPassword = 'pasword';
$dbConnection = new DB($dbHost, $dbUser, $dbName, $dbPassword);
var_export($dbConnection);


