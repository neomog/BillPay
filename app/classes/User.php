<?php declare(strict_types=1);

namespace App\classes;

class User
{
    private $db;

    private $userId;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    public function getUserDetails($userIdentifier)
    {
        $getUserQuery = "SELECT id, first_name, last_name, user_name, email, gender, mobile, type, status, api_key, date_created, date_updated FROM users WHERE id = ? OR api_key = ?";
        $getUserParams = [
            $userIdentifier,
            $userIdentifier
        ];
        return $this->db->fetchRow($getUserQuery, $getUserParams);

    }
    public function getUserIdByApiKey($apiKey): int
    {
        $getUserId = "SELECT id FROM users WHERE api_key = ?";
        $getUserIdParams = [
            $apiKey
        ];
        $getUserIdResult = $this->db->fetchRow($getUserId, $getUserIdParams);
        return $getUserIdResult['id'];
    }

    // TODO: move to wallet class
    public function getUserWalletBalance($apiKey)
    {
        $userId = $this->getUserIdByApiKey($apiKey);
        $getWalletQuery = "SELECT wallet_balance FROM user_wallet WHERE user_id = ?";
        $getWalletParams = [
            $userId
        ];
        $getWalletResult = $this->db->fetchRow($getWalletQuery, $getWalletParams);
        return $getWalletResult['wallet_balance'];
    }

    public function chargeUserWallet($apiKey, $amount): bool
    {
        $userId = $this->getUserIdByApiKey($apiKey);
        $chargeUserQuery = "UPDATE user_wallet SET wallet_balance = wallet_balance - ? WHERE user_id = ?";
        $chargeUserParams = [
            $amount,
            $userId
        ];
        return $this->db->executeQuery($chargeUserQuery, $chargeUserParams);
    }

}