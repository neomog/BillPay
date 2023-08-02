<?php declare(strict_types=1);

namespace App\classes;

class User
{
    private $db;
    private $userId;
    private $requestData;

    public function __construct(DB $db, array $requestData)
    {
        $this->db = $db;
        $this->userId = $requestData['userId'];
        $this->requestData = $requestData;
    }

    public function getUserDetails()
    {
        $getUserQuery = "SELECT id, first_name, last_name, user_name, email, gender, mobile, type, status, api_key, date_created, date_updated FROM users WHERE id = ?";
        $getUserParams = [
            $this->userId
        ];
        return $this->db->fetchRow($getUserQuery, $getUserParams);
    }

    public function getAllUserDetails()
    {
        $getAllUserQuery = "SELECT id, first_name, last_name, user_name, email, gender, mobile, type, status, api_key, date_created, date_updated FROM users";
        $getAllUserParams = [];
        return $this->db->fetchArray($getAllUserQuery, $getAllUserParams);
    }

    Public function deleteUser()
    {
//        $userIdentifier
        $deleteUserQuery = "DELETE FROM user WHERE id = ?";
        $deleteUserParams = [
            $this->userId
        ];
        return $this->db->executeQuery($deleteUserQuery, $deleteUserParams);
    }

    public function createUser()
    {
        $Auth = new Auth($this->db, $this->requestData);
        return $Auth->register();
    }

    public function updateUser()
    {
        $updateUserQuery = "UPDATE users SET first_name = ?, last_name = ?, user_name = ?, type = ? WHERE id = ?";
        $updateUserParams = [
            $this->requestData['firstName'],
            $this->requestData['lastName'],
            $this->requestData['userName'],
            $this->userId
        ];
        return $this->db->executeQuery($updateUserQuery, $updateUserParams);
    }
    public function getUserIdByApiKey(): array
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