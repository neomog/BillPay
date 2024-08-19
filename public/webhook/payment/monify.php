<?php

// {
//     "eventData": {
//         "product": {
//             "reference": "111222333",
//             "type": "OFFLINE_PAYMENT_AGENT"
//         },
//         "transactionReference": "MNFY|76|20211117154810|000001",
//         "paymentReference": "0.01462001097368737",
//         "paidOn": "17/11/2021 3:48:10 PM",
//         "paymentDescription": "Mockaroo Jesse",
//         "metaData": {},
//         "destinationAccountInformation": {},
//         "paymentSourceInformation": {},
//         "amountPaid": 78000,
//         "totalPayable": 78000,
//         "offlineProductInformation": {
//             "code": "41470",
//             "type": "DYNAMIC"
//         },
//         "cardDetails": {},
//         "paymentMethod": "CASH",
//         "currency": "NGN",
//         "settlementAmount": 77600,
//          "paymentStatus": "PAID",
//         "customer": {
//             "name": "Mockaroo Jesse",
//             "email": "111222333@ZZAMZ4WT4Y3E.monnify"
//         }
//     },
//     "eventType": "SUCCESSFUL_TRANSACTION"
// }

define("ROOT", dirname(__DIR__));
require ROOT . '/../../vendor/autoload.php';


use App\classes\Utility;

$Utility = new Utility();

$dbConnection = $Utility->getConnection();

$requestData = file_get_contents("php://input");
if(!is_string($requestData)){
    // TODO: Log content into our log file
}

$data = json_decode($requestData, true);

if(!empty($data)){
    if(isset($data["eventType"]) && $data["eventType"] == "SUCCESSFUL_TRANSACTION"){
        $txStatus = $data['eventData']['paymentStatus'] ?? '';
        $amount = $data['eventData']['amountPaid'] ?? '';
        $currency = $data['eventData']['currency'] ?? '';
        $txRef = $data['eventData']['product']['reference'] ?? '';
        $paymentMethod = $data['eventData']['paymentMethod'] ?? '';
        // $gatewayMessage

        $userEmail = $data['eventData']['customer']['email'] ?? '';

        $payload = json_encode($data);

        echo $txRef;
        // TODO: update databse 
    }
}