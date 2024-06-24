<?php



define("ROOT", dirname(__DIR__));
require ROOT . '/../../vendor/autoload.php';


use App\classes\Utility;

$Utility = new Utility();

$dbConnection = $Utility->getConnection();
/**
 *  Sample payload
 */

$dataRecieved = file_get_contents("php://input");

if(!is_string($dataRecieved)) {
//    TODO Log content into our log file
}

//define('PAYSTACK_SECRET_KEY', 'sk_test_cc1b0fc5f41395326e70463305c7460624faaac0');

// http_response_code(200);

$data = json_decode($dataRecieved, true);


if(!empty($data)) {

    if (isset($data['event']) && $data['event'] === 'charge.success') {

        $id = $data['data']['id'] ?? '';

        $txStatus = $data['data']['status'] ?? '';
        $amount = $data['data']['amount'] ?? '';
        $currency = $data['data']['currency'] ?? '';
        $txRef = $data['data']['reference'] ?? '';
        $paymentMethod = $data['data']['channel'] ?? '';
        $gatewayMessage = $data['data']['gateway_response'];

//    Customer information
        $userId = $data['data']['customer']['id'];


//    Payload
        $payLoad = json_encode($data);

//        TODO: SAVE IN THE DATABASE
        $addPaymentQuery = "INSERT INTO `wallet_transaction` (`user_id`, `payment_gateway`, `w_request_id`, 
                                  `w_transaction_reference`, `status`, `w_transaction_amount`, `w_transaction_type`, 
                                  `w_transaction_method`, `w_transaction_message`, `gateway_response`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $addPaymentParams = [
            $userId,
            'paystack',
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

//        var_export("I got here");
////var_export($dataRecieved);
//        die();
        if($txStatus === 'success'){
            if($addPaymentResult){
                echo json_encode([
                    'status' => true,
                    'server_response' => 'Success',
                    'server_message' => "Payment made successfully"
                ]);
            }
        }else if($txStatus === 'failure') {
            if($addPaymentResult){
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


