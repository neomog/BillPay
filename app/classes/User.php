<?php declare(strict_types=1);

namespace App\classes;

class User
{
    private $db;

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

    public function getAllUserDetails()
    {
        $getAllUserQuery = "SELECT id, first_name, last_name, user_name, email, gender, mobile, type, status, api_key, date_created, date_updated FROM users";
        $getAllUserParams = [];
        return $this->db->fetchArray($getAllUserQuery, $getAllUserParams);
    }

    Public function deleteUser($userIdentifier)
    {
        $deleteUserQuery = "DELETE FROM user WHERE id = ? OR api_key = ?";
        $deleteUserParams = [
            $userIdentifier,
            $userIdentifier
        ];
        return $this->db->executeQuery($deleteUserQuery, $deleteUserParams);
    }

    public function createUser($data)
    {
        $Auth = new Auth($this->db, $data);
        return $Auth->register();
    }

    public function updateUser($apiKey)
    {
        $userId = $this->getUserIdByApiKey($apiKey);
        $updateUserQuery = "UPDATE users SET first_name = ?, last_name = ?, user_name = ?, type = ? WHERE id = ?";
        $updateUserParams = [
            $this->firstName,
            $this->lastName,
            $this->userName,
            $userId
        ];
        return $this->db->executeQuery($updateUserQuery, $updateUserParams);
    }

    public function getUserIdByApiKey($apiKey): array
    {
        $getUserId = "SELECT id FROM users WHERE api_key = ?";
        $getUserIdParams = [
            $apiKey
        ];
        return $this->db->fetchRow($getUserId, $getUserIdParams);
    }

}