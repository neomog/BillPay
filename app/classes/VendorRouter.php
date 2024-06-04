<?php

namespace App\classes;

use App\classes\model\Vendor;

/**
 * Vendor
 *
 *
 * @package Controller
 * @author     OHIARE NATHANIEL <ohiarenathaniel@gmail.com>
 */
class VendorRouter {
    public function __construct(private DB $db, private array $requestData = []) {

    }

    public function getVendors(): string
    {
        $vendorObject = new Vendor($this->db);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $vendorObject->getVendors()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function addVendor(): string
    {
        $vendorObject = new Vendor($this->db, $this->requestData);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $vendorObject->addVendor()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function updateVendor(): string
    {
        $vendorObject = new Vendor($this->db, $this->requestData);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $vendorObject->updateVendor()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function deleteVendor(): string
    {
        $vendorObject = new Vendor($this->db, $this->requestData);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $vendorObject->deleteVendor()
        ];
        return Helper::jsonResponse($responseData);
    }

    /**
     *
     * Adds or update vendor code
     *
     * @return string
     */
    public function addUpdateVendorCode(): string
    {
        $vendorObject = new Vendor($this->db, $this->requestData);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $vendorObject->addUpdateVendorCode()
        ];
        return Helper::jsonResponse($responseData);
    }
}