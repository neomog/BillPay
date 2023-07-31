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

    public function __construct($db, $data)
    {
        if ($this->verifyUser($data['apiKey'])) {
            $this->db = $db;
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

    }

    public function balance()
    {
        return $this->vendor->balance();
    }

    public function getServices()
    {
        return $this->vendor->getServices();
    }

    public function getServiceOptions()
    {
        return $this->vendor->getServiceOptions();
    }

    public function getVariationCodes()
    {
        return $this->vendor->getVariationCodes();
    }

    public function airtime()
    {
        $this->recordTransaction('airtime');
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
            return Helper::jsonResponse($responseData);
        }
    }

    public function data()
    {
        $this->recordTransaction('data');
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

    public function education()
    {
        $this->recordTransaction('education');
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

    public function electricity()
    {
        $this->recordTransaction('electricity');
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

    public function recordTransaction($transactionType)
    {
        $User = new User($this->db);
        $userIdRequest = $User->getUserIdByApiKey($this->data_input['apiKey']);
        $insertTransactionQuery = "INSERT INTO recharge_transactions (user_id, vendor, request_id, status, transaction_amount, transaction_type, payment_method, date_created, date_updated) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        $insertTransactionParams = [
            $userIdRequest['id'],
            "vtpass",
            $this->data_input['requestId'],
            "pending",
            $this->data_input['amount'],
            $transactionType,
            "wallet"
        ];
        return $this->db->executeQuery($insertTransactionQuery, $insertTransactionParams);
    }

    private function verifyUser(mixed $apiKey)
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