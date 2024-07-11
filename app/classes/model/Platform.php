<?php

namespace App\classes\model;

use App\classes\DB;

/**
 * PlatformSettings
 *
 * @desc Manages all platform settings logic
 * @package Model
 * @author     OHIARE NATHANIEL <ohiarenathaniel@gmail.com>
 */
class Platform {

    public function __construct(private DB $db, private array $requestData = []) {

    }

    /**
     *
     * @desc Get platform settings
     *
     * @return array
     */
    public function getPlatformSettings(): array
    {
        $query = "SELECT * FROM platform_settings";
        $params = [];
        $result = $this->db->fetchAll($query, $params);
        if ($result) {
            return $result;
        }
        return [];
    }

    /**
     *
     * Adds or update platform settings
     *
     * @return bool
     */
    public function addUpdatePlatformSettings(): bool
    {
        $identifier = $this->requestData['identifier'] ?? 0;
        $params = [
            $this->requestData['setting_name'],
            $this->requestData['setting_description'],
            $this->requestData['setting_status'],
            $this->requestData['setting_editable']
        ];

        if ($identifier > 0) {
            $query = "UPDATE platform_settings SET setting_name = ?, setting_description = ?, setting_status = ?, setting_editable = ? WHERE id = ?";
            $params[] = $identifier;
        } else {
            $query = "INSERT INTO platform_settings (setting_name, setting_description, setting_status, setting_editable) VALUES (?, ?, ?, ?)";
        }

        return $this->db->executeQuery($query, $params);
    }

    /**
     *
     * Delete platform settings
     *
     * @return bool
     */
    public function deletePlatformSettings(): bool
    {
        $identifier = $this->requestData['identifier'] ?? 0;

        if ($identifier > 0) {
            $deleteQuery = "DELETE FROM platform_settings WHERE id = ?";
            $deleteParams[] = $identifier;

            return $this->db->executeQuery($deleteQuery, $deleteParams);
        }

        return false;
    }


}