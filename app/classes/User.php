<?php declare(strict_types=1);

namespace App\classes;

use App\classes\vendors\Vtpass;
use App\classes\Router;

class User
{
    private $db;
    private $userId;
    private $requestData;
    private $apiKey;

    public function __construct(DB $db, array $requestData)
    {
        $this->db = $db;
        $this->requestData = $requestData;
//        $this->requestData = $requestData;
        if ($this->getUserIdByApiKey()) {
            $this->userId = $this->getUserIdByApiKey();
            $this->apiKey = $requestData['apiKey'];

        } else {
            $responseData = [
                'status' => false,
                'server_response' => 'Failed',
                'server_message' => "Unauthorised user",
            ];
            return Helper::jsonResponse($responseData);
        }
    }

    public function getUserDetails(): string
    {
        $getUserQuery = "SELECT id, first_name, last_name, user_name, email, gender, mobile, type, status, api_key, date_created, date_updated FROM users WHERE id = ?";
        $getUserParams = [
            $this->userId
        ];
        return json_encode($this->db->fetchRow($getUserQuery, $getUserParams));
    }

    public function getAllUserDetails(): string
    {
        $getAllUserQuery = "SELECT id, first_name, last_name, user_name, email, gender, mobile, type, status, api_key, date_created, date_updated FROM users";
        $getAllUserParams = [];
        return json_encode($this->db->fetchAll($getAllUserQuery, $getAllUserParams));
    }

    Public function deleteUser(): bool
    {
//        $userIdentifier
        $deleteUserQuery = "DELETE FROM users WHERE id = ?";
        $deleteUserParams = [
            $this->userId
        ];
        return $this->db->executeQuery($deleteUserQuery, $deleteUserParams);
    }

    public function createUser(): string
    {
        $Auth = new Auth($this->db, $this->requestData);
        return $Auth->register();
    }

    public function updateUser(): bool
    {
        $updateUserQuery = "UPDATE users SET first_name = ?, last_name = ?, user_name = ?, type = ? WHERE id = ?";
        $updateUserParams = [
            $this->requestData['firstName'],
            $this->requestData['lastName'],
            $this->requestData['userName'],
            $this->requestData['userType'],
            $this->userId
        ];
        return $this->db->executeQuery($updateUserQuery, $updateUserParams);
    }
    public function getUserIdByApiKey(): int
    {
        $getUserId = "SELECT id FROM users WHERE api_key = ?";
        $getUserIdParams = [
            $this->requestData['apiKey']
        ];
        $getUserIdResult = $this->db->fetchRow($getUserId, $getUserIdParams);
        return $getUserIdResult['id'];
    }

    // TODO: move to wallet class
    public function getUserWalletBalance()
    {
        $getWalletQuery = "SELECT wallet_balance FROM user_wallet WHERE user_id = ?";
        $getWalletParams = [
            $this->userId
        ];
        $getWalletResult = $this->db->fetchRow($getWalletQuery, $getWalletParams);
        return $getWalletResult['wallet_balance'];
    }

    public function chargeUserWallet(): bool
    {
        $chargeUserQuery = "UPDATE user_wallet SET wallet_balance = wallet_balance - ? WHERE user_id = ?";
        $chargeUserParams = [
            $this->requestData['amount'],
            $this->userId
        ];
        return $this->db->executeQuery($chargeUserQuery, $chargeUserParams);
    }

}