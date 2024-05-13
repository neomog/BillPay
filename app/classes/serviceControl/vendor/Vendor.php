<?php

namespace App\classes\serviceControl\vendor;

use App\classes\DB;
class Vendor {

    public function __construct(private DB $db, private array $requestData = []) {

    }

    public function getVendors() {
        $getVendorsQuery = "SELECT * FROM `vendor`";
        $getVendorsParams = [];
        $getVendorsResult = $this->db->fetchAll($getVendorsQuery, $getVendorsParams);

        if($getVendorsResult) {
            return $getVendorsResult;
        }
        return [];
    }

    public function addVendor() {
        $addVendorQuery = "INSERT INTO `vendor` (`vendor_name`, `vendor_code`, `requirement`, `status`, `description`, `image`, `date_created`) VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $addVendorParams = [
            $this->requestData['vendorName'],
            $this->requestData['vendorCode'],
            $this->requestData['requirement'],
            $this->requestData['status'],
            $this->requestData['description'],
            $this->requestData['image']
        ];

        $addVendorResult = $this->db->executeQuery($addVendorQuery, $addVendorParams);

        if ($addVendorResult) {
            return true;
        }
        return false;
    }

    public function updateVendor() {
        $updateVendorQuery = "UPDATE `vendor` SET `vendor_name` = ?, `vendor_code` = ?, `requirement` = ?, `status` = ?, `description` = ?, `image` = ?, `date_updated` = NOW() WHERE `id` = ?";
        $updateVendorParams = [
            $this->requestData['vendorName'],
            $this->requestData['vendorCode'],
            $this->requestData['requirement'],
            $this->requestData['status'],
            $this->requestData['description'],
            $this->requestData['image'],
            $this->requestData['identifier']
        ];

        $updateVendorResult = $this->db->executeQuery($updateVendorQuery, $updateVendorParams);

        if ($updateVendorResult) {
            return true;
        }
        return false;
    }

    public function deleteVendor() {
        $deleteVendorQuery = "DELETE FROM `vendor` WHERE `id` = ?";
        $deleteVendorParams = [
            $this->requestData['identifier']
        ];

        $deleteVendorResult =  $this->db->executeQuery($deleteVendorQuery, $deleteVendorParams);

        if ($deleteVendorResult) {
            return true;
        }
        return false;
    }

}