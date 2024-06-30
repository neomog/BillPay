<?php

namespace App\classes\vendors;

class Vtpass
{
    /**
     * @var string
     *
     * For GET request you’ll need to pass the api-key and public-key through the request header.
     * api-key: xxxxxxxxxxxxxxxxxxxx
     * public-key: PK_xxxxxxxxxxxxxxxxx
     * For POST request you’ll need to pass the api-key and secret-key through the request header.
     * api-key: xxxxxxxxxxxxxxxxxxxx
     * secret-key: SK_xxxxxxxxxxxxxxxxx
     */
//    private string $sandboxApiUrl = 'https://sandbox.vtpass.com/api/';
//    private string $liveApiUrl = 'https://api-service.vtpass.com/api/';
//    private string $sandboxApiKey = 'f1ed61f05c5ba14a2c081f598ad775e2';
//    private string $sandboxPublicKey = 'PK_636963a72ead3f3484737be28cb5a15356b7a197b96';
//    private string $sandboxPrivateKey = 'SK_6684ac50b30b77b4b947ded7beab301cab7e5191365';

    public function __construct(private array $requestData)
    {
    }

    public function balance(): array
    {
        $host = $this->requestData['vendorData']['vendor_host'] . '/balance';
        return $this->getRequest($host);
    }

    public function getServices(): array
    {
        $host = $this->requestData['vendorData']['vendor_host'] . '/service-categories';
        return $this->getRequest($host);
    }

    public function getServiceOptions(): array
    {
        $serviceId = $this->requestData['serviceCode']; // e.g data, airtime
        $host = $this->requestData['vendorData']['vendor_host'] . '/services?identifier=' . $serviceId;
        return $this->getRequest($host);
    }

    public function getVariationCodes(): array
    {
        $serviceOptionId = $this->requestData['optionCode']; // e.g gotv
        $host = $this->requestData['vendorData']['vendor_host'] . '/service-variations?serviceID=' . $serviceOptionId;
        return $this->getRequest($host);
    }

    public function airtime(): array
    {
        // no variation code
        $serviceOptionId = $this->requestData['optionCode']; // mtn, airtel, glo, 9mobile
        $host = $this->requestData['vendorData']['vendor_host'] . '/pay';
        $vendorRequestData = [
            "request_id" => $this->requestData['requestId'],
            "serviceID" => $serviceOptionId,
            "amount" => $this->requestData['amount'],
            "phone" => $this->requestData['destinationNumber']
        ];
        return $this->postRequest($host, $vendorRequestData);
    }

    public function data(): array
    {
        $variationId = $this->requestData['variationCode']; // mtn, airtel, glo, 9mobile
        $host = $this->requestData['vendorData']['vendor_host'] . '/pay';
        $vendorRequestData = [
            "request_id" => $this->requestData['requestId'],
            "serviceID" => $this->requestData['optionCode'],
            "billersCode" => $this->requestData['destinationNumber'],
            "variation_code" => $variationId,
            "amount" => $this->requestData['amount'],
            "phone" => $this->requestData['destinationNumber']
        ];
        return $this->postRequest($host, $vendorRequestData);
    }

    public function education()
    {
        return true;
    }

    public function electricity()
    {
        return true;
    }

    public function getRequest($host): array
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
        return json_decode(curl_exec($curl), true);
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
        $vendorResponse = curl_exec($curl);

        return $this->formatVendorResponse($vendorResponse);
    }

    public function formatVendorResponse($vendorResponse)
    {
        return json_decode($vendorResponse, true);
    }

    public function __destruct()
    {

    }

}
