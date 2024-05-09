<?php

namespace App\classes;

use App\classes\serviceControl\Service;

class ServiceRouter
{

    // TODO:  move method to become independent

    public function __construct(private DB $db, private array $requestData)
    {

    }

    public function getServices(): string
    {
        $serviceObject = new Service($this->db);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $serviceObject->getServices()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function addService(): string
    {
        $serviceObject = new Service($this->db, $this->requestData);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $serviceObject->addService()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function updateService(): string
    {
        $serviceObject = new Service($this->db, $this->requestData);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $serviceObject->updateService()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function deleteService(): string
    {
        $serviceObject = new Service($this->db, $this->requestData);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $serviceObject->deleteService()
        ];
        return Helper::jsonResponse($responseData);
    }

    // START OF SERVICE OPTION LOGIC

    public function getServiceOptions(): string
    {
        $serviceObject = new Service($this->db);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $serviceObject->getServices()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function addServiceOption(): string
    {
        $serviceObject = new Service($this->db, $this->requestData);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $serviceObject->addService()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function updateServiceOption(): string
    {
        $serviceObject = new Service($this->db, $this->requestData);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $serviceObject->updateService()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function deleteServiceOption(): string
    {
        $serviceObject = new Service($this->db, $this->requestData);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $serviceObject->deleteService()
        ];
        return Helper::jsonResponse($responseData);
    }

    // START OF SERVICE OPTION LOGIC

    public function getServiceOptionCodes(): string
    {
        $serviceObject = new Service($this->db);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $serviceObject->getServices()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function addServiceOptionCode(): string
    {
        $serviceObject = new Service($this->db, $this->requestData);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $serviceObject->addService()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function updateServiceOptionCode(): string
    {
        $serviceObject = new Service($this->db, $this->requestData);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $serviceObject->updateService()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function deleteServiceOptionCode(): string
    {
        $serviceObject = new Service($this->db, $this->requestData);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $serviceObject->deleteService()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function __destruct()
    {

    }



}