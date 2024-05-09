<?php

namespace App\classes\serviceControl;

use App\Classes\Db;

class ServiceOption
{

    public function __construct(private DB $db, private array $requestData = [])
    {
    }

    public function getServiceOptions(): array
    {
        $getServiceOptionsQuery = "SELECT * FROM service_option";
        $getServiceOptionsParams = [];
        $getServiceOptionsResult = $this->db->fetchAll($getServiceOptionsQuery, $getServiceOptionsParams);
        if ($getServiceOptionsResult) {
            return $getServiceOptionsResult;
        }
        return [];
    }

    public function addServiceOption(): bool
    {
        $serviceId = $this->requestData['serviceId'];
        $name = $this->requestData['name'];
        $code = $this->requestData['code'];
        $status = $this->requestData['status'];
        $description = $this->requestData['description'];
        $image = $this->requestData['image'];
        $api = $this->requestData['api'];

        $addServiceOptionQuery = "INSERT INTO service_option (service_id, name, code, status, description, image, api, date_created) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $addServiceOptionParams = [
            $serviceId,
            $name,
            $code,
            $status,
            $description,
            $image,
            $api
        ];
        return $this->db->executeQuery($addServiceOptionQuery, $addServiceOptionParams);
    }

    public function updateServiceOption(): bool
    {
        $serviceId = $this->requestData['serviceId'];
        $name = $this->requestData['name'];
        $code = $this->requestData['code'];
        $status = $this->requestData['status'];
        $description = $this->requestData['description'];
        $image = $this->requestData['image'];
        $api = $this->requestData['api'];
        $identifier = $this->requestData['identifier'];

        $updateServiceOptionQuery = "UPDATE service_option SET service_id = ?, name = ?, code = ?, status = ?, description = ?, image = ?, api = ? WHERE id = ?";
        $updateServiceOptionParams = [
            $serviceId,
            $name,
            $code,
            $status,
            $description,
            $image,
            $api,
            $identifier
        ];

        return $this->db->executeQuery($updateServiceOptionQuery, $updateServiceOptionParams);
    }

    public function deleteServiceOption(): bool
    {
        $deleteServiceOptionQuery = "DELETE FROM service_option WHERE id = ?";
        $deleteServiceOptionParams = [
            $this->requestData['identifier']
        ];

        if ($this->db->executeQuery($deleteServiceOptionQuery, $deleteServiceOptionParams)) {
            return true;
        }
        return false;
    }
}