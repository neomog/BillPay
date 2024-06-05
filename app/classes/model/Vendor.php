<?php

namespace App\classes\model;

use App\classes\DB;

/**
 * Vendor
 *
 *
 * @package Model
 * @author     OHIARE NATHANIEL <ohiarenathaniel@gmail.com>
 */
class Vendor {

    public function __construct(private DB $db, private array $requestData = []) {

    }

    public function getVendors(): array
    {
        $getVendorsQuery = "SELECT * FROM `vendor`";
        $getVendorsParams = [];
        $getVendorsResult = $this->db->fetchAll($getVendorsQuery, $getVendorsParams);

        if($getVendorsResult) {
            return $getVendorsResult;
        }
        return [];
    }

    public function addVendor(): bool
    {
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

    public function updateVendor(): bool
    {
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

    public function deleteVendor(): bool
    {
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

    /**
     *
     * Adds or update vendor code
     *
     * @return boolean
     */
    public function addUpdateVendorCode(): bool
    {
        $identifier = $this->requestData['identifier'] ?? 0;
        if ($identifier > 0) {
            $updateVendorQuery = "UPDATE `vendor` SET `vendor_code_mapping` = ? WHERE `id` = ?";
            $updateVendorParams = [
                json_encode($this->requestData['vendorCodeMapping']),
                $this->requestData['identifier']
            ];

            $updateVendorResult = $this->db->executeQuery($updateVendorQuery, $updateVendorParams);

            if ($updateVendorResult) {
                return true;
            }
            return false;
        }

        return false;

    }

}