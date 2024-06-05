<?php

namespace App\classes;

use App\classes\model\Service;
use App\classes\model\ServiceOption;
use App\classes\model\ServiceOptionCode;

/**
 * author: ohiare nathaniel
 * This class controls the platform rendered
 * services, including adding a new service
 * adding a service option, adding a service
 * option code
 */
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
        $serviceOptionObject = new ServiceOption($this->db);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $serviceOptionObject->getServiceOptions()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function addServiceOption(): string
    {
        $serviceOptionObject = new ServiceOption($this->db, $this->requestData);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $serviceOptionObject->addServiceOption()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function updateServiceOption(): string
    {
        $serviceOptionObject = new ServiceOption($this->db, $this->requestData);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $serviceOptionObject->updateServiceOption()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function deleteServiceOption(): string
    {
        $serviceOptionObject = new ServiceOption($this->db, $this->requestData);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $serviceOptionObject->deleteServiceOption()
        ];
        return Helper::jsonResponse($responseData);
    }

    // START OF SERVICE OPTION LOGIC

    public function getServiceOptionCodes(): string
    {
        $serviceOptionCodeObject = new ServiceOptionCode($this->db);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $serviceOptionCodeObject->getServiceOptionCodes()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function addServiceOptionCode(): string
    {
        $serviceOptionCodeObject = new ServiceOptionCode($this->db, $this->requestData);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $serviceOptionCodeObject->addServiceOptionCode()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function updateServiceOptionCode(): string
    {
        $serviceOptionCodeObject = new ServiceOptionCode($this->db, $this->requestData);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $serviceOptionCodeObject->updateServiceOptionCode()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function deleteServiceOptionCode(): string
    {
        $serviceOptionCodeObject = new ServiceOptionCode($this->db, $this->requestData);
        $responseData = [
            'status' => true,
            'server_response' => 'Success',
            'server_message' => "Action completed successfully",
            'data' => $serviceOptionCodeObject->deleteServiceOptionCode()
        ];
        return Helper::jsonResponse($responseData);
    }

    public function __destruct()
    {

    }



}