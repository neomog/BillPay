<?php

namespace App\classes;

class Request
{

    private array $requestHeader;
    private array $requestData;

    public function __construct(array $requestHeader, array $requestData)
    {
        $this->requestHeader = !empty($requestHeader) ?? $requestHeader;
        $this->requestData = !empty($requestHeader) ?? $requestData;
    }

    public function vtpass()
    {

    }

    public function __destruct()
    {

    }

}