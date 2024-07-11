<?php

namespace App\classes\model;

use App\classes\DB;

class ServiceOptionCode
{

    public function __construct(private DB $db, private array $requestData = [])
    {
    }

    public function getServiceOptionCodes(): array
    {
        $getServiceOptionCodesQuery = "SELECT * FROM service_option_code";
        $getServiceOptionCodesParams = [];
        $getServiceOptionCodesResult = $this->db->fetchAll($getServiceOptionCodesQuery, $getServiceOptionCodesParams);
        if ($getServiceOptionCodesResult) {
            return $getServiceOptionCodesResult;
        }
        return [];
    }

    public function getServiceOptionCode(): array
    {
        $identifier = $this->requestData['serviceOptionCode'];
        $query = "SELECT * FROM service_option_code WHERE id = ?";
        $params[] = $identifier;
        $result = $this->db->fetchRow($query, $params);
        if ($result) {
            return $result;
        }
        return [];
    }

    public function addServiceOptionCode(): bool
    {
        $serviceOptionId = $this->requestData['serviceOptionId'];
        $name = $this->requestData['name'];
        $code = $this->requestData['code'];
        $status = $this->requestData['status'];
        $description = $this->requestData['description'];
        $image = $this->requestData['image'];
        $api = $this->requestData['api'];

        $addServiceOptionCodeQuery = "INSERT INTO service_option_code (service_option_id, name, code, status, description, image, api, date_created, date_updated) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        $addServiceOptionCodeParams = [
            $serviceOptionId,
            $name,
            $code,
            $status,
            $description,
            $image,
            $api
        ];
        return $this->db->executeQuery($addServiceOptionCodeQuery, $addServiceOptionCodeParams);
    }

    public function updateServiceOptionCode(): bool
    {
        $serviceOptionId = $this->requestData['serviceOptionId'];
        $name = $this->requestData['name'];
        $code = $this->requestData['code'];
        $status = $this->requestData['status'];
        $description = $this->requestData['description'];
        $image = $this->requestData['image'];
        $api = $this->requestData['api'];
        $identifier = $this->requestData['identifier'];

        $updateServiceOptionCodeQuery = "UPDATE service_option SET service_id = ?, name = ?, code = ?, status = ?, description = ?, image = ?, api = ? WHERE id = ?";
        $updateServiceOptionCodeParams = [
            $serviceOptionId,
            $name,
            $code,
            $status,
            $description,
            $image,
            $api,
            $identifier
        ];

        return $this->db->executeQuery($updateServiceOptionCodeQuery, $updateServiceOptionCodeParams);
    }

    public function deleteServiceOptionCode(): bool
    {
        $deleteServiceOptionCodeQuery = "DELETE FROM service_option WHERE id = ?";
        $deleteServiceOptionCodeParams = [
            $this->requestData['identifier']
        ];

        if ($this->db->executeQuery($deleteServiceOptionCodeQuery, $deleteServiceOptionCodeParams)) {
            return true;
        }
        return false;
    }

    /**
     *
     * @desc Get platform service option code map from platform
     *
     * @return array
     */
    public function getServiceOptionCodeMap(): array
    {
        $platformObject = new Platform($this->db);
        // TODO: Not sure if below indexing will work, test to confirm
        $map = $platformObject->getPlatformSettings()['platform_service_option_code_map'];
        if (!empty($map) && gettype($map) == 'string') {
            return json_decode($map, true);
        }
        return [];
    }
}