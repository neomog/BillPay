<?php

define("ROOT", dirname(__DIR__));
require ROOT . '/../../vendor/autoload.php';

use App\classes\Utility;

$Utility = new Utility();

$dbConnection = $Utility->getConnection();
// SAMPLE DATA
//$data = json_decode(
//   ' {
//  "event": "charge.completed",
//  "data": {
//    "id": 285959875,
//    "tx_ref": "Links-616626414629",
//    "flw_ref": "PeterEkene/FLW270177170",
//    "device_fingerprint": "a42937f4a73ce8bb8b8df14e63a2df31",
//    "amount": 100,
//    "currency": "NGN",
//    "charged_amount": 100,
//    "app_fee": 1.4,
//    "merchant_fee": 0,
//    "processor_response": "Approved by Financial Institution",
//    "auth_model": "PIN",
//    "ip": "197.210.64.96",
//    "narration": "CARD Transaction ",
//    "status": "successful",
//    "payment_type": "card",
//    "created_at": "2020-07-06T19:17:04.000Z",
//    "account_id": 17321,
//    "customer": {
//        "id": 215604089,
//      "name": "Yemi Desola",
//      "phone_number": null,
//      "email": "user@gmail.com",
//      "created_at": "2020-07-06T19:17:04.000Z"
//    },
//    "card": {
//        "first_6digits": "123456",
//      "last_4digits": "7889",
//      "issuer": "VERVE FIRST CITY MONUMENT BANK PLC",
//      "country": "NG",
//      "type": "VERVE",
//      "expiry": "02/23"
//    }
//  }
//}', true
//);
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
//header("Access-Control-Allow-Headers: Content-Type");
//header("Access-Control-Allow-Credentials: true");
//
//file_get_contents('php://input');
//phpinfo();
//$data = file_get_contents("php://input");

$dataRecieved = file_get_contents("php://input");

// TODO Check that input is a string
if(!is_string($dataRecieved)) {
//    TODO Log content into our log file
}
$data = json_decode($dataRecieved, true);

if(!empty($data)){

    if(isset($data['event']) && $data['event'] === "charge.completed"){

//        TRANSACTION STATUS
        $txStatus = $data['data']['status'] ?? '';

//        TRANSACTION DETAILS
        $id = $data['data']['id'] ?? '';
        $txRef = $data['data']['tx_ref'] ?? '';
        $paymentMethod = $data['data']['payment_type'] ?? '';
        $amount = $data['data']['amount'] ?? '';
        $currency = $data['data']['currency'] ?? '';
        $chargedAmount = $data['data']['charged_amount'] ?? '';
        $gatewayMessage = $data['data']['processor_response'];

//        CUSTOMER DETAILS
        $userId = $data['data']['customer']['id'];

//        ALL DATA
        $payLoad = json_encode($data);


//        TODO: SAVE IN THE DATABASE
        $addPaymentQuery = "INSERT INTO `wallet_transaction` (`user_id`, `payment_gateway`, `w_request_id`, 
                                  `w_transaction_reference`, `status`, `w_transaction_amount`, `w_transaction_type`, 
                                  `w_transaction_method`, `w_transaction_message`, `gateway_response`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $addPaymentParams = [
            $userId,
            'flutterwave',
            $id,
            $txRef,
            $txStatus,
            $amount,
            'credit',
            $paymentMethod,
            $gatewayMessage,
            $payLoad
        ];
        $addPaymentResult = $dbConnection->executeQuery($addPaymentQuery, $addPaymentParams);


        if($txStatus === 'success'){
            if($addPaymentResult) {
                echo json_encode([
                    'status' => true,
                    'server_response' => 'Success',
                    'server_message' => "Payment made successfully"
                ]);
            }
        }else if($txStatus === 'failed') {
            if($addPaymentResult) {
                echo json_encode([
                    'status' => false,
                    'server_response' => 'Failed',
                    'server_message' => "payment unsuccessful"
                ]);
            }
        }
    }
    http_response_code(200);
}
