<?php declare(strict_types=1);

namespace App\classes;

class Wallet
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getUserWalletBalance($identifier)
    {
        $getWalletQuery = "SELECT wallet_balance FROM user_wallet WHERE user_id = ?";
        $getWalletParams = [
            $identifier
        ];
        $getWalletResult = $this->db->fetchRow($getWalletQuery, $getWalletParams);
        return $getWalletResult['wallet_balance'];
    }

    public function chargeUserWallet($identifier, $amount): bool
    {
        $chargeUserQuery = "UPDATE user_wallet SET wallet_balance = wallet_balance - ? WHERE user_id = ?";
        $chargeUserParams = [
            $amount,
            $identifier
        ];
        return $this->db->executeQuery($chargeUserQuery, $chargeUserParams);
    }

    public function __destruct()
    {

    }

}