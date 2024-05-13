<?php

namespace App\classes;

use App\classes\serviceControl\vendor\Vendor;
class VendorRouter {
    public function __construct(private DB $db, private array $requestData = []) {

    }

    public function getVendors() {
        $vendorObject = new Vendor($this->db);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $vendorObject->getVendors()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function addVendor() {
        $vendorObject = new Vendor($this->db, $this->requestData);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $vendorObject->addVendor()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function updateVendor() {
        $vendorObject = new Vendor($this->db, $this->requestData);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $vendorObject->updateVendor()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function deleteVendor() {
        $vendorObject = new Vendor($this->db, $this->requestData);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $vendorObject->deleteVendor()
        ];
        return Helper::jsonResponse($responseData);
    }
}