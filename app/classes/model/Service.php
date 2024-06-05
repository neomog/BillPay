<?php

namespace App\classes\model;

use App\classes\DB;

class Service
{

    public function __construct(private DB $db, private array $requestData = [])
    {
    }

    public function getServices(): array
    {
        $getServicesQuery = "SELECT * FROM service";
        $getServicesParams = [];
        $getServicesResult = $this->db->fetchAll($getServicesQuery, $getServicesParams);
        if ($getServicesResult) {
            return $getServicesResult;
        }
        return [];
    }

    public function addService(): bool
    {
        // Todo: should be able to add multiple services

        $name = $this->requestData['name'];
        $code = $this->requestData['code'];
        $status = $this->requestData['status'];
        $description = $this->requestData['description'];
        $image = $this->requestData['image'];
        $api = $this->requestData['api'];

        $addQuery = "INSERT INTO service (name, code, status, description, image, api, date_created) VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $addParams = [
            $name,
            $code,
            $status,
            $description,
            $image,
            $api
        ];

        return $this->db->executeQuery($addQuery, $addParams);
    }

    public function updateService(): bool
    {
        // Todo: should be able to update multiple services

        $name = $this->requestData['name'];
        $code = $this->requestData['code'];
        $status = $this->requestData['status'];
        $description = $this->requestData['description'];
        $image = $this->requestData['image'];
        $api = $this->requestData['api'];
        $identifier = $this->requestData['identifier'];

        $addQuery = "UPDATE service SET name = ?, code = ?, status = ?, description = ?, image = ?, api = ? WHERE id = ?";
        $addParams = [
            $name,
            $code,
            $status,
            $description,
            $image,
            $api,
            $identifier
        ];

        return $this->db->executeQuery($addQuery, $addParams);
    }

    public function deleteService(): bool
    {
        // Todo: should be able to delete multiple services
        $deleteServiceQuery = "DELETE FROM service WHERE id = ?";
        $deleteServiceParams = [
            $this->requestData['identifier']
        ];

        if ($this->db->executeQuery($deleteServiceQuery, $deleteServiceParams)) {
            return true;
        }
        return false;
    }

}