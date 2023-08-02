<?php

namespace App\classes\vendors;

class Vtpass
{
    //For GET request you’ll need to pass the api-key and public-key through the request header.
//api-key: xxxxxxxxxxxxxxxxxxxx
//public-key: PK_xxxxxxxxxxxxxxxxx
//For POST request you’ll need to pass the api-key and secret-key through the request header.
//api-key: xxxxxxxxxxxxxxxxxxxx
//secret-key: SK_xxxxxxxxxxxxxxxxx
    private string $sandboxApiUrl = 'https://sandbox.vtpass.com/api/';
    private string $liveApiUrl = 'https://api-service.vtpass.com/api/';
    private string $sandboxApiKey = 'f1ed61f05c5ba14a2c081f598ad775e2';
    private string $sandboxPublicKey = 'PK_636963a72ead3f3484737be28cb5a15356b7a197b96';
    private string $sandboxPrivateKey = 'SK_6684ac50b30b77b4b947ded7beab301cab7e5191365';
    private string $liveApiKey = 'https://api-service.vtpass.com/api/';
    private string $requestId;
    private string $serviceCode;
    private string $serviceOptionCode;
    private string $variationCode;
    private string $destinationNumber;
    private string $amount;

    public function __construct(array $data)
    {
        $this->requestId = $data['requestId'] ?? '';
        $this->serviceCode = $data['serviceCode'] ?? '';
        $this->serviceOptionCode = $data['optionCode'] ?? '';
        $this->variationCode = $data['variationCode'] ?? '';
        $this->destinationNumber = $data['destinationNumber'] ?? '';
        $this->amount = $data['amount'] ?? '';
    }

    public function balance(): bool|string
    {
        $host = $this->sandboxApiUrl . 'balance';
        return $this->getRequest($host);
    }

    public function getServices(): bool|string
    {
        $host = $this->sandboxApiUrl . 'service-categories';
        return $this->getRequest($host);
    }

    public function getServiceOptions(): bool|string
    {
        $serviceId = $this->serviceCode; // e.g data, airtime
        $host = $this->sandboxApiUrl . 'services?identifier=' . $serviceId;
        return $this->getRequest($host);
    }

    public function getVariationCodes(): bool|string
    {
        $serviceOptionId = $this->serviceOptionCode; // e.g gotv
        $host = $this->sandboxApiUrl . 'service-variations?serviceID=' . $serviceOptionId;
        return $this->getRequest($host);
    }

    public function airtime(): bool|string
    {
        $serviceOptionId = $this->serviceOptionCode; // mtn, airtel, glo, 9mobile
        $host = $this->sandboxApiUrl . 'pay';
        $requestData = [
            "request_id" => $this->requestId,
            "serviceID" => $serviceOptionId,
            "amount" => $this->amount,
            "phone" => $this->destinationNumber
        ];
        return $this->postRequest($host, $requestData);
    }

    public function data(): bool|string
    {
        $variationId = $this->variationCode; // mtn, airtel, glo, 9mobile
        $host = $this->sandboxApiUrl . 'pay';
        $requestData = [
            "request_id" => $this->requestId,
            "serviceID" => $this->serviceCode,
            "billersCode" => $this->destinationNumber,
            "variation_code" => $variationId,
            "amount" => $this->amount,
            "phone" => $this->destinationNumber
        ];
        return $this->postRequest($host, $requestData);
    }

    public function education()
    {
        return true;
    }

    public function electricity()
    {
        return true;
    }

    public function getRequest($host)
    {
        $headers = array(
            'Content-Type: application/json',
            'api-key: ' . $this->sandboxApiKey,
            'public-key: ' . $this->sandboxPublicKey,
        );

        $curl = curl_init();
        $curl_option_array = array(
            CURLOPT_URL => $host,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        );
        curl_setopt_array($curl, $curl_option_array);
        return curl_exec($curl);
    }

    public function postRequest($host, $requestData)
    {
        $headers = array(
            'api-key: ' . $this->sandboxApiKey,
            'secret-key: ' . $this->sandboxPrivateKey,
        );

        $curl = curl_init();
        $curl_option_array = array(
            CURLOPT_URL => $host,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $requestData
        );
        curl_setopt_array($curl, $curl_option_array);
        return curl_exec($curl);
    }

    public function __destruct()
    {

    }

}
