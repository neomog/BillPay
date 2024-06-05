<?php

namespace App\classes;

use App\classes\model\Platform;

/**
 * PlatformRouter
 *
 * @desc This manages all platform settings
 * @package Controller
 * @author     OHIARE NATHANIEL <ohiarenathaniel@gmail.com>
 */
class PlatformRouter {
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
     * @desc Adds or update platform settings
     *
     * @return string
     */
    public function addUpdatePlatformSettings(): string
    {
        $platformObject = new Platform($this->db, $this->requestData);
        $platformObject->addUpdatePlatformSettings();
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => []
        ];
        return Helper::jsonResponse($responseData);
    }

    /**
     *
     * @desc Delete setting
     *
     * @return string
     */
    public function deletePlatformSettings(): string
    {
        $platformObject = new Platform($this->db, $this->requestData);
        $platformObject->deletePlatformSettings();
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => []
        ];
        return Helper::jsonResponse($responseData);
    }

}