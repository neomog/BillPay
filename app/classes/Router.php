<?php

namespace App\classes;

use App\classes\vendors\Vtpass;

class Router
{

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
    private $data_input;
    private $apiKey;
    private $amount;

    public function __construct($db, $data)
    {
        $this->db = $db;
        if ($this->checkUserExist($data['apiKey'])) {
            $this->apiKey = $data['apiKey'];
            $this->amount = $data['amount'];
            $Helper = new Helper();
            $data['requestId'] = $Helper->generateRequestId();
            $this->data_input = $data;
            $this->vendor = new Vtpass($data);
        } else {
            $responseData = [
                'status' => false,
                'server_response' => 'Failed',
                'server_message' => "Unauthorised user",
            ];
            return Helper::jsonResponse($responseData);
        }
        // TODO: write function that processes transaction and calls other functions

    }

    public function balance(): string
    {
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $this->vendor->balance()
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

    public function airtime()
    {
        $processRequest = $this->recordTransaction('airtime');
        $checkProcessRequest = json_decode($processRequest, true);
        if ($checkProcessRequest['data']['status']) {
            $vendorResponse = $this->vendor->airtime();
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

    public function data(): string
    {
        $processRequest = $this->recordTransaction('data');
        $checkProcessRequest = json_decode($processRequest, true);
        if ($checkProcessRequest['data']['status']) {
            $vendorResponse = $this->vendor->data();
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

    public function recordTransaction($transactionType)
    {
        $User = new User($this->db);
        $Wallet = new Wallet($this->db);

        // check that user has sufficient funds to perform operation
        $walletBalance = $Wallet->getUserWalletBalance($this->apiKey);
        if ($this->amount > $walletBalance) {
            $responseData = [
                'status' => false,
                'server_response' => 'Failed',
                'server_message' => "Action failed: insufficient funds",
                'data' => []
            ];
            return Helper::jsonResponse($responseData, 400);
        }

        // charge user wallet before rendering service
        $chargeUser = $Wallet->chargeUserWallet($this->apiKey, $this->amount);
        if ($chargeUser) {
            // record recharge transaction
            $userIdRequest = $User->getUserIdByApiKey($this->data_input['apiKey']);
            $insertTransactionQuery = "INSERT INTO recharge_transactions (user_id, vendor, request_id, status, transaction_amount, transaction_type, payment_method, date_created, date_updated) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            $insertTransactionParams = [
                $userIdRequest,
                "vtpass",
                $this->data_input['requestId'],
                "pending",
                $this->data_input['amount'],
                $transactionType,
                "wallet"
            ];
            return $this->db->executeQuery($insertTransactionQuery, $insertTransactionParams);
        }
        $responseData = [
            'status' => false,
            'server_response' => 'Failed',
            'server_message' => "Action failed: unable to charge user",
            'data' => []
        ];
        return Helper::jsonResponse($responseData, 400);

    }

    private function checkUserExist(mixed $apiKey): bool
    {
        $User = new User($this->db);
        if ($User->getUserIdByApiKey($apiKey)) {
            return true;
        } else {
            return false;
        }
    }

    private function verifyUser(mixed $apiKey): bool
    {
        $User = new User($this->db);
        if ($User->getUserIdByApiKey($apiKey)) {
            return true;
        } else {
            return false;
        }
    }

    public function __destruct()
    {

    }



}