<?php

namespace App\classes;

use App\classes\vendors\Vtpass;

class ServiceRouter
{


    // TODO:  move method to become independent
//For GET request you’ll need to pass the api-key and public-key through the request header.
//api-key: xxxxxxxxxxxxxxxxxxxx
//public-key: PK_xxxxxxxxxxxxxxxxx
//For POST request you’ll need to pass the api-key and secret-key through the request header.
//api-key: xxxxxxxxxxxxxxxxxxxx
//secret-key: SK_xxxxxxxxxxxxxxxxx
    private string $sandboxApiUrl = 'https://sandbox.vtpass.com/api/';
    private string $liveApiUrl = 'https://api-service.vtpass.com/api/';
    private string $sandboxApiKey = 'https://api-service.vtpass.com/api/';
    private string $liveApiKey = 'https://api-service.vtpass.com/api/';
    private $vendor;
    private $db;
    private $apiKey;
    private $amount;
    private $requestData;
    private $requestId;

    public function __construct($db, $requestData)
    {
        $this->db = $db;
        $this->requestData = $requestData;
        $this->apiKey = $requestData['apiKey'] ?? "";
        $this->amount = $requestData['amount'] ?? "";
        $Helper = new Helper();
        $this->requestId = $requestData['requestId'] = $Helper->generateRequestId();
        // TODO: get vendor dynamic to make robust
        // $vendor = $requestData['vendor'];
        // $this->vendor = new $vendor($requestData);
        // TODO: write a method that is called to handle all
        // operation before routing
        $this->vendor = new Vtpass($requestData);
    // TODO: write function that processes transaction and calls other functions

    }

    public function vendorBalance(): string
    {
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $this->vendor->balance()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function balance(): string
    {
        $User = new User($this->db, $this->requestData);
        $Wallet = new Wallet($this->db);
        $userId = $User->getUserIdByApiKey();

        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => number_format($Wallet->getUserWalletBalance($userId), 2)
        ];
        return Helper::jsonResponse($responseData);
    }

    public function getServices(): string
    {
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $this->vendor->getServices()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function getServiceOptions(): string
    {
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $this->vendor->getServiceOptions()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function getVariationCodes(): string
    {
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $this->vendor->getVariationCodes()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function airtime(): string
    {
        $processRequest = $this->recordTransaction('airtime');
        $checkProcessRequest = json_decode($processRequest, true);
        if ($checkProcessRequest) {
            $vendorResponse = $this->vendor->airtime();
            if ($vendorResponse) {
                $updateRecord = json_decode($this->updateTransactionRecord($vendorResponse), true);
                if (!$updateRecord['data']['status']) {
                    $responseData = [
                        'status' => false,
                        'server_response' => 'failed',
                        'server_message' => "Client Error: Invalid request",
                        'data' => $updateRecord
                    ];
                    return Helper::jsonResponse($responseData, 400);
                }

                $responseData = [
                    'status' => true,
                    'server_response' => 'Success',
                    'server_message' => "Action completed, awaiting vendor confirmation",
                    'data' => $vendorResponse
                ];
                return Helper::jsonResponse($responseData);
            } else {
                $responseData = [
                    'status' => false,
                    'server_response' => 'Failed',
                    'server_message' => "Action failed, either vendor error or network error",
                    'data' => $vendorResponse
                ];
                return Helper::jsonResponse($responseData, 400);
            }
        }


        return $processRequest;

    }

    public function data(): string
    {
        $processRequest = $this->recordTransaction('data');

        if ($processRequest['status']) {
            $vendorResponse = $this->vendor->data();
            if ($vendorResponse) {
                $responseData = [
                    'status' => true,
                    'server_response' => 'Success',
                    'server_message' => "Action completed, awaiting vendor confirmation",
                    'data' => $vendorResponse
                ];
                return Helper::jsonResponse($responseData);
            }

            $responseData = [
                'status' => false,
                'server_response' => 'Failed',
                'server_message' => "Action failed, either vendor error or network error",
                'data' => $vendorResponse
            ];
            return Helper::jsonResponse($responseData, 400);
        }
    }

    public function education(): string
    {
        $processRequest = $this->recordTransaction('education');
        $checkProcessRequest = json_decode($processRequest, true);
        if ($checkProcessRequest['data']['status']) {
            $vendorResponse = $this->vendor->education();
            if ($vendorResponse) {
                $responseData = [
                    'status' => true,
                    'server_response' => 'Success',
                    'server_message' => "Action completed, awaiting vendor confirmation",
                    'data' => $vendorResponse
                ];
                return Helper::jsonResponse($responseData);
            } else {
                $responseData = [
                    'status' => false,
                    'server_response' => 'Failed',
                    'server_message' => "Action failed, either vendor error or network error",
                    'data' => $vendorResponse
                ];
                return Helper::jsonResponse($responseData, 400);
            }
        }
        return $processRequest;
    }

    public function electricity(): string
    {
        $processRequest = $this->recordTransaction('electricity');
        $checkProcessRequest = json_decode($processRequest, true);
        if ($checkProcessRequest['data']['status']) {
            $vendorResponse = $this->vendor->electricity();
            if ($vendorResponse) {
                $responseData = [
                    'status' => true,
                    'server_response' => 'Success',
                    'server_message' => "Action completed, awaiting vendor confirmation",
                    'data' => $vendorResponse
                ];
                return Helper::jsonResponse($responseData);
            } else {
                $responseData = [
                    'status' => false,
                    'server_response' => 'Failed',
                    'server_message' => "Action failed, either vendor error or network error",
                    'data' => $vendorResponse
                ];
                return Helper::jsonResponse($responseData, 400);
            }
        }
        return $processRequest;
    }

    public function recordTransaction($transactionType): array
    {
        $User = new User($this->db, $this->requestData);
        $Wallet = new Wallet($this->db);
        $userId = $User->getUserIdByApiKey();

        // check that user has sufficient funds to perform operation
        $walletBalance = $Wallet->getUserWalletBalance($userId);
        if ($this->amount > $walletBalance) {
            $responseData = [
                'status' => false,
                'server_response' => 'Failed',
                'server_message' => "Action failed: insufficient funds",
                'data' => []
            ];
        }

        // charge user wallet before rendering service
        $chargeUser = $Wallet->chargeUserWallet($userId, $this->amount);
        if ($chargeUser) {
            // record recharge transaction
            $userIdRequest = $User->getUserIdByApiKey();
            $insertTransactionQuery = "INSERT INTO recharge_transaction (user_id, vendor, request_id, status, transaction_amount, transaction_type, payment_method, date_created, date_updated) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            $insertTransactionParams = [
                $userIdRequest,
                "vtpass",
                $this->requestId,
                "pending",
                $this->requestData['amount'],
                $transactionType,
                "wallet"
            ];

            if ($this->db->executeQuery($insertTransactionQuery, $insertTransactionParams)) {
                $responseData = [
                    'status' => true,
                    'server_response' => 'Success',
                    'server_message' => "User charge completed",
                ];
            }
        } else {
            $responseData = [
                'status' => false,
                'server_response' => 'Failed',
                'server_message' => "Action failed: unable to charge user",
                'data' => []
            ];
        }

        return $responseData;

    }

    /**
     * @param $vendorResponse
     * @return string|bool
     */
    public function updateTransactionRecord($vendorResponse): string|bool
    {
        if ($vendorResponse['code'] == 012 || '012') {
            $responseData = [
                'status' => false,
                'server_response' => 'Failed',
                'server_message' => $vendorResponse['response_description'],
                'data' => []
            ];
            return Helper::jsonResponse($responseData, 400);
        }
        // update transaction record
        $updateTransactionQuery = "UPDATE recharge_transaction SET transaction_id = ?, vendor_response = ?, phone_number = ? WHERE request_id = ?";
        $updateTransactionParams = [
            $vendorResponse['content']['transactions']['transactionId'],
            json_encode($vendorResponse),
            $vendorResponse['content']['transactions']['unique_element'],
            $vendorResponse['requestId']
        ];
        $updateTransactionResponse = $this->db->executeQuery($updateTransactionQuery, $updateTransactionParams);

        if (!$updateTransactionResponse) {
            $responseData = [
                'status' => false,
                'server_response' => 'Failed',
                'server_message' => "Action failed: unable to update record",
                'data' => []
            ];
            return Helper::jsonResponse($responseData, 400);
        }

        return true;

    }

    public function __destruct()
    {

    }



}