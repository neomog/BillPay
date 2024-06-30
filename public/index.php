<?php declare(strict_types=1);
//error_Reporting(E_ERROR | E_NOTICE);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

define("ROOT", dirname(__DIR__));

require ROOT . '/vendor/autoload.php';

use App\classes\User;
use App\classes\Helper;
use App\classes\Utility;
use App\classes\ErrorHandler;

set_error_handler(array(ErrorHandler::class, 'handleError'));
set_exception_handler(array(ErrorHandler::class, 'handleException'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $env = Utility::getEnv();
    define('DEBUG', $env['DEBUG']);
    try {


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
            'changePassword' => 'UserRouter',
            'adminUpdateUser' => 'UserRouter',
            'adminCreateUser' => 'UserRouter',


            // SERVICES
            'getServices' => 'PurchaseRouter',
            'getServiceOptions' => 'PurchaseRouter',
            'getVariationCodes' => 'PurchaseRouter',
            'airtime' => 'PurchaseRouter',
            'data' => 'PurchaseRouter',
            'education' => 'PurchaseRouter',
            'electricity' => 'PurchaseRouter',

            //VENDOR
            'getVendors' => 'VendorRouter',
            'addVendor' => 'VendorRouter',
            'updateVendor' => 'VendorRouter',
            'deleteVendor' => 'VendorRouter',

            // VENDOR CODE MAPPING
            'addUpdateVendorCode' => 'VendorRouter',

            // PLATFORM CORE SERVICES
//           'getServices' => 'ServiceRouter',
           'addService' => 'ServiceRouter',
           'updateService' => 'ServiceRouter',
           'deleteService' => 'ServiceRouter',
//           'getServiceOptions' => 'ServiceRouter',
           'addServiceOption' => 'ServiceRouter',
           'updateServiceOption' => 'ServiceRouter',
           'deleteServiceOption' => 'ServiceRouter',
           'getServiceOptionCodes' => 'ServiceRouter',
           'addServiceOptionCode' => 'ServiceRouter',
           'updateServiceOptionCode' => 'ServiceRouter',
           'deleteServiceOptionCode' => 'ServiceRouter',

            // PLATFORM SETTINGS
            'getPlatformSettings' => 'PlatformRouter',
            'addUpdatePlatformSettings' => 'PlatformRouter',
            'deletePlatformSettings' => 'PlatformRouter',

            // Add more endpoints here as needed
        ];
        // TODO: generate request id

        $callback = !empty($apiRequest['process']) ? $apiRequest['process'] : null;

        if (array_key_exists($callback, $endpoints)) {
            $callbackClass = 'App\classes\\' . $endpoints[$callback];

            if (class_exists($callbackClass)) {
// TODO: IMPLEMENT REQUEST OBJECT AND RESPONSE OBJECT
                if ($callback == 'login' || $callback == 'register') {
                    $classInstance = new $callbackClass($dbConnection, $apiRequest);
                    $response = $classInstance->$callback();
                } else {
                    // TODO: IMPLEMENT API KEY INTO HEADER INSTEAD OR REQUEST OBJECT
                    $apiKey = filter_var(Helper::getBearerToken() ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                    if (User::checkUserExists($dbConnection, $apiKey) > 0) {
                        $apiRequest['apiKey'] = $apiKey;
                        $classInstance = new $callbackClass($dbConnection, $apiRequest);
                        $response = $classInstance->$callback();
                    } else {
                        $response = Helper::jsonResponse(['error' => 'Invalid api key'], 401);
                    }
                }

            } else {
                // Handle endpoint not found with a 404 status
                $response = Helper::jsonResponse(['error' => 'Class ' . $callbackClass . ' not found'], 404);
            }
        } else {
            // Handle endpoint not found with a 404 status
            $response = Helper::jsonResponse(['error' => 'Endpoint not found ', 'myRequest' => $apiRequest], 405);
        }

// Output the response
        echo $response;


    } catch (Exception|Error $e) {
        if (DEBUG == 'true') {
            echo "<pre> \n";
            echo "=========================== \n";
            echo "   DEBUG MODE TURNED ON     \n";
            echo "=========================== \n";
            var_export($e->getMessage());
            var_export($e->getTraceAsString());
            echo "\n </pre>";
        }
    }
} else {
    echo '<pre>';
    echo 'SEEING THIS MEANS YOU GOT IN';
    echo "\n";
    echo 'NB: METHOD NOT ALLOWED';
    echo '</pre>';
}

