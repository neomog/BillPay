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
    public function getUserIdByApiKey($apiKey): array
    {
        $getUserId = "SELECT id FROM users WHERE api_key = ?";
        $getUserIdParams = [
            $apiKey
        ];
        return $this->db->fetchRow($getUserId, $getUserIdParams);
    }

}