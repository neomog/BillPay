<?php declare(strict_types=1);

namespace App\classes;

class Wallet
{
    private DB $db;
    private string $balance;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    /**
     * @param $identifier
     * @return int|float
     */
    public function getUserWalletBalance($identifier): string
    {
        $getWalletQuery = "SELECT wallet_balance FROM user_wallet WHERE user_id = ?";
        $getWalletParams = [
            $identifier
        ];
        $getWalletResult = $this->db->fetchRow($getWalletQuery, $getWalletParams);
        if ($getWalletResult) {
            $this->balance = $getWalletResult['wallet_balance'];
            return $getWalletResult['wallet_balance'];
        }
        return '0.00';
    }

    /**
     * @param $serviceCost
     * @return bool
     */
    public function checkSufficientBalance($serviceCost): bool
    {
        $balance = $this->balance;
        if ($serviceCost < $balance) {
            return true;
        }
        return false;
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