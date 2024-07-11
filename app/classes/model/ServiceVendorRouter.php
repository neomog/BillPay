<?php

namespace App\classes\model;

use App\classes\DB;

class ServiceVendorRouter
{
    private DB $db;

    public function __construct(DB $db)
    {
        $this->db = $db;
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

    public function addService(array $data): bool
    {
        // Todo: should be able to add multiple services

        $name = $data['name'];
        $code = $data['code'];
        $status = $data['status'];
        $description = $data['description'];
        $image = $data['image'];
        $api = $data['api'];

        $addQuery = "INSERT INTO service (name, code, status, description, image, api, date_created, date_updated) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
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

    public function updateService(array $data): bool
    {
        // Todo: should be able to update multiple services

        $name = $data['name'];
        $code = $data['code'];
        $status = $data['status'];
        $description = $data['description'];
        $image = $data['image'];
        $api = $data['api'];
        $identifier = $data['identifier'];

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

    public function deleteService($identifier): bool
    {
        // Todo: should be able to delete multiple services
        $deleteServiceQuery = "DELETE FROM service WHERE id = ?";
        $deleteServiceParams = [
            $identifier
        ];

        if ($this->db->executeQuery($deleteServiceQuery, $deleteServiceParams)) {
            return true;
        }
        return false;
    }

}