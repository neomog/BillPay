<?php

namespace App\classes;
/**
 * @author ohiare nathaniel
 * @desc to serve as a request builder
 * @return object
 */
class RequestBuilder
{
    private DB $db;
    private array $requestHeader;
    private array $requestData;
    private ?string $apiResponse;

    public function __construct(DB $db = null)
    {
        $this->db = $db;
        $this->requestData = [];
    }

    public function setUserData(array $userData)
    {
        $this->requestData['user'] = $userData;
        return $this;
    }

    public function setVendorData(array $vendorData)
    {
        $this->requestData['vendor'] = $vendorData;
        return $this;
    }

    public function setPlatformData(array $platformData)
    {
        $this->requestData['platform'] = $platformData;
        return $this;
    }

    public function setApiRequest(string $apiEndpoint)
    {
        $requestData = json_encode($this->requestData);
        // Example: $this->apiResponse = makeApiRequest($apiEndpoint, $requestData);
        $this->apiResponse = '{"status": "success", "data": {"example_key": "example_value"}}'; // Replace with the actual API response
        return $this;
    }

    public function getApiResponse(): ?string
    {
        return $this->apiResponse;
    }

    public function __destruct()
    {
        // Any cleanup logic, if needed
    }
}
