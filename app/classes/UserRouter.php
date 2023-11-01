<?php

namespace App\classes;

class UserRouter
{

    private $db;
    private $requestData;
    private User $User;
    private $requestId;

    public function __construct($db, $requestData)
    {
        $this->db = $db;
        $this->requestData = $requestData;
        $this->User = new User($this->db, $this->requestData);
//        else {
//            $responseData = [
//                'status' => false,
//                'server_response' => 'Failed',
//                'server_message' => "Unauthorised user",
//            ];
//            return Helper::jsonResponse($responseData);
//        }

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

    public function updateUser(): string
    {
        $User = new User($this->db, $this->requestData);
        $updateUser = $User->updateUser();

        if ($updateUser) {
            $responseData = [
                'status' => true,
                'server_response' => 'Success',
                'server_message' => "Action completed successfully",
                'data' => json_decode($User->getUserDetails())
            ];
        } else {
            $responseData = [
                'status' => false,
                'server_response' => 'Failed',
                'server_message' => "Action failed"
            ];
        }


        return Helper::jsonResponse($responseData);
    }

    public function activate(): string
    {
        $User = $this->User;
        if (!$User->checkUserExist()) {
            $responseData = [
                'status' => false,
                'server_response' => 'Failed',
                'server_message' => "Unauthorised user",
            ];
            return Helper::jsonResponse($responseData, 401);

        }
        $activate = $User->activateUser();

        if ($activate) {
            $responseData = [
                'status' => true,
                'server_response' => 'Success',
                'server_message' => "Action completed successfully"
            ];
        } else {
            $responseData = [
                'status' => false,
                'server_response' => 'Failed',
                'server_message' => "Action failed"
            ];
        }
        return Helper::jsonResponse($responseData);
    }

    public function deactivate(): string
    {
        $User = new User($this->db, $this->requestData);
        $activate = $User->deactivateUser();

        if ($activate) {
            $responseData = [
                'status' => true,
                'server_response' => 'Success',
                'server_message' => "Action completed successfully"
            ];
        } else {
            $responseData = [
                'status' => false,
                'server_response' => 'Failed',
                'server_message' => "Action failed"
            ];
        }
        return Helper::jsonResponse($responseData);
    }

    public function delete(): string
    {
        $User = new User($this->db, $this->requestData);
        $delete = $User->deleteUser();

        if ($delete) {
            $responseData = [
                'status' => true,
                'server_response' => 'Success',
                'server_message' => "Action completed successfully"
            ];
        } else {
            $responseData = [
                'status' => false,
                'server_response' => 'Failed',
                'server_message' => "Action failed"
            ];
        }
        return Helper::jsonResponse($responseData);
    }

    private function verifyUser(mixed $apiKey): bool
    {
        $User = new User($this->db, $this->requestData);
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