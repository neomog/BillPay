<?php declare(strict_types=1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

define("ROOT", dirname(__DIR__));

require ROOT . '/vendor/autoload.php';

use App\classes\DB;
use App\classes\Helper;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $apiRequest = array();
    $text_data = file_get_contents("php://input");
    $text_data_array = json_decode($text_data, true);
    $apiRequest = (array)$apiRequest + (array)$text_data_array;


    $dbHost = 'mysql';
    $dbUser = 'root';
    $dbName = 'billpay';
    $dbPassword = 'rootpassword';
    $dbConnection = new DB($dbHost, $dbUser, $dbName, $dbPassword);

// Define your API endpoints and their corresponding callbacks
    $endpoints = [
        'register' => 'Auth',
        'login' => 'Auth',
        'resetPassword' => 'Auth',
        'balance' => 'Router',
        'getServices' => 'Router',
        'getServiceOptions' => 'Router',
        'getVariationCodes' => 'Router',
        'airtime' => 'Router',
        'data' => 'Router',
        'education' => 'Router',
        'electricity' => 'Router',
        'getUserDetails' => 'User',
        'getAllUserDetails' => 'User',
        'deleteUser' => 'User',
        'createUser' => 'User',
        'updateUser' => 'User',
        'getUserWalletBalance' => 'User'
        // Add more endpoints here as needed
    ];
    // TODO: generate request id

    $callback = !empty($apiRequest['process']) ? $apiRequest['process'] : null;

    if (array_key_exists($callback, $endpoints)) {
        $callbackClass = 'App\classes\\' . $endpoints[$callback];

        if (class_exists($callbackClass)) {
            $classInstance = new $callbackClass($dbConnection, $apiRequest);
            $response = $classInstance->$callback();
        } else {
            // Handle endpoint not found with a 404 status
            $response = Helper::jsonResponse(['error' => 'Class '.$callbackClass.' not found'], 404);
        }
    } else {
        // Handle endpoint not found with a 404 status
        $response = Helper::jsonResponse(['error' => 'Endpoint not found ', 'myRequest' => $apiRequest], 404);
    }


// Output the response
    echo $response;
} else {
    echo '<pre>';
    echo 'SEEING THIS MEANS YOU GOT IN';
    echo '</pre>';
}

