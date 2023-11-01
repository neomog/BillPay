<?php declare(strict_types=1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

define("ROOT", dirname(__DIR__));

require ROOT . '/vendor/autoload.php';

use App\classes\User;
use App\classes\DB;
use App\classes\Helper;
use App\classes\Utility;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $apiRequest = array();
    $text_data = file_get_contents("php://input");
    $text_data_array = json_decode($text_data, true);
    $apiRequest = (array)$apiRequest + (array)$text_data_array;

    $Utility = new Utility();
    $dbConnection = $Utility->getConnection();

// Define your API endpoints and their corresponding callbacks
    $endpoints = [
        // AUTH
        'register' => 'Auth',
        'login' => 'Auth',
        'resetPassword' => 'Auth',

        // USER
        'deactivate' => 'UserRouter',
        'activate' => 'UserRouter',
        'delete' => 'UserRouter',
        'balance' => 'UserRouter',
        'getUserDetails' => 'UserRouter',
        'getAllUserDetails' => 'UserRouter',
        'deleteUser' => 'UserRouter',
        'createUser' => 'UserRouter',
        'updateUser' => 'UserRouter',
        'getUserWalletBalance' => 'UserRouter',

        // SERVICES
        'getServices' => 'ServiceRouter',
        'getServiceOptions' => 'ServiceRouter',
        'getVariationCodes' => 'ServiceRouter',
        'airtime' => 'ServiceRouter',
        'data' => 'ServiceRouter',
        'education' => 'ServiceRouter',
        'electricity' => 'ServiceRouter'

        // Add more endpoints here as needed
    ];
    // TODO: generate request id

    $callback = !empty($apiRequest['process']) ? $apiRequest['process'] : null;

    if (array_key_exists($callback, $endpoints)) {
        $callbackClass = 'App\classes\\' . $endpoints[$callback];

        if (class_exists($callbackClass)) {

            if ($callback !== ('login' || 'register')) {
                $classInstance = new $callbackClass($dbConnection, $apiRequest);
                $response = $classInstance->$callback();
            } else {
                $apiKey = filter_var($apiRequest['apiKey'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "";
                if (User::checkUserExists($dbConnection, $apiKey)) {
                    $classInstance = new $callbackClass($dbConnection, $apiRequest);
                    $response = $classInstance->$callback();
                } else {
                    $response = Helper::jsonResponse(['error' => 'Invalid api key'], 401);
                }
            }

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
    echo "\n";
    echo 'NB: METHOD NOT ALLOWED';
    echo '</pre>';
}

