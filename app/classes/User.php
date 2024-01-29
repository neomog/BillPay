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
        $this->userId = $this->getUserIdByApiKey();
        $this->apiKey = $requestData['apiKey'];
    }

    public function getUserDetails(): string
    {
        $getUserQuery = "SELECT id, first_name, last_name, user_name, email, gender, mobile, type, status, api_key, date_created, date_updated FROM user WHERE id = ?";
        $getUserParams = [
            $this->userId
        ];
        return json_encode($this->db->fetchRow($getUserQuery, $getUserParams));
    }

    public function getAllUserDetails(): string
    {
        $getAllUserQuery = "SELECT id, first_name, last_name, user_name, email, gender, mobile, type, status, api_key, date_created, date_updated FROM user";
        $getAllUserParams = [];
        return json_encode($this->db->fetchAll($getAllUserQuery, $getAllUserParams));
    }

    Public function deleteUser(): bool
    {
//        $userIdentifier
        $deleteUserQuery = "DELETE FROM user WHERE id = ?";
        $deleteUserParams = [
            $this->userId
        ];

        if ($this->db->executeQuery($deleteUserQuery, $deleteUserParams)) {
            return true;
        }
        return false;
    }

    public function createUser(): string
    {
        $Auth = new Auth($this->db, $this->requestData);
        return $Auth->register();
    }

    /**
     * @return bool
     */
    public function adminUpdateUser(): bool
    {
        $updateUserQuery = "UPDATE user SET first_name = ?, last_name = ?, user_name = ?, type = ? WHERE id = ?";
        $updateUserParams = [
            $this->requestData['firstName'],
            $this->requestData['lastName'],
            $this->requestData['userName'],
            $this->requestData['userType'],
            $this->userId
        ];
        return $this->db->executeQuery($updateUserQuery, $updateUserParams);
    }

    /**
     * @return bool
     */
    public function updateUser(): bool
    {
        $updateUserQuery = "UPDATE user SET first_name = ?, last_name = ?, user_name = ? WHERE id = ?";
        $updateUserParams = [
            $this->requestData['firstName'],
            $this->requestData['lastName'],
            $this->requestData['userName'],
            $this->userId
        ];

        if ($this->db->executeQuery($updateUserQuery, $updateUserParams)) {
            return true;
        }
        return false;
    }

    public function getUserIdByApiKey(): int
    {
        $getUserIdQuery = "SELECT id FROM user WHERE api_key = ?";
        $getUserIdParams = [
            $this->requestData['apiKey']
        ];
        $getUserIdResult = $this->db->fetchRow($getUserIdQuery, $getUserIdParams);
        if ($getUserIdResult) {
            return $getUserIdResult['id'];
        }
        return 0;

    }

    public static function checkUserExists(DB $db, $apiKey): int
    {
        $getUserIdQuery = "SELECT id FROM user WHERE api_key = ?";
        $getUserIdParams = [
            $apiKey
        ];
        $getUserIdResult = $db->fetchRow($getUserIdQuery, $getUserIdParams);
        if ($getUserIdResult > 0) {
            return $getUserIdResult['id'];
        }
        return 0;

    }

    public function checkUserExist(): bool
    {
        if ($this->getUserIdByApiKey()) {
            return true;
        } else {
            return false;
        }
    }

    public function activateUser(): bool
    {
        $activateUserQuery = "UPDATE user SET status = ? WHERE id = ?";
        $activateUserParams = [
            'active',
            $this->userId
        ];

        if ($this->db->executeQuery($activateUserQuery, $activateUserParams)) {
            return true;
        }
        return false;
    }

    public function deactivateUser(): bool
    {
        $activateUserQuery = "UPDATE user SET status = ? WHERE id = ?";
        $activateUserParams = [
            'deactivated',
            $this->userId
        ];

        if ($this->db->executeQuery($activateUserQuery, $activateUserParams)) {
            return true;
        }
        return false;
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

    public function changePassword() {
        $data = $this->requestData;
        if(!empty($data['oldPassword'])){
            $typedInOldPassword = filter_var($data['oldPassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $typedInOldPasswordHash = hash('sha512', $typedInOldPassword);

            $getOldPasswordQuery = "SELECT password, salt FROM user WHERE id = ?";
            $getOldPasswordParams = [$this->userId];
            $getOldPasswordResult = $this->db->fetchRow($getOldPasswordQuery, $getOldPasswordParams);
            $oldPassword = $getOldPasswordResult['password'];
            $dbPasswordSalt = $getOldPasswordResult['salt'];
            $savedPassword = hash('sha512', $typedInOldPasswordHash . $dbPasswordSalt);

            $newPassword = $data['newPassword'];
            $newPasswordHash = hash('sha512', $newPassword);
            $passwordEncrypt = hash('sha512', $newPasswordHash . $dbPasswordSalt);

            if($savedPassword == $oldPassword){
                $changePasswordQuery = "UPDATE user SET password = ?";
                $changePasswordParams = [
                    $passwordEncrypt
                ];
                return $this->db->executeQuery($changePasswordQuery, $changePasswordParams);
            }
        }
    }

}