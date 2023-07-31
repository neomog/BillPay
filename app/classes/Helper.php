<?php

namespace App\classes;

class Helper
{
    /**
     * Generate a JSON response.
     *
     * @param mixed $data The data to be encoded as JSON.
     * @param int $status The HTTP status code for the response.
     * @return string The JSON-encoded response.
     */
    public static function jsonResponse(mixed $data, int $status = 200): string
    {
        http_response_code($status);
        return json_encode(['data' => $data, 'status' => $status]);
    }

    function generateRequestId() {
        $uniqueKey = $this->generateCode(5); // Assuming generateCode() generates a unique code of 5 characters.
        date_default_timezone_set('Africa/Lagos');
        $africa_lagos_offset = 3600; // GMT+1 is 1 hour ahead (3600 seconds)
        $timestamp_lagos = gmdate("YmdHis", time() + $africa_lagos_offset);
        return $timestamp_lagos . $uniqueKey;
    }

    /**
     * @throws \Exception
     */
    public function generateCode($length): string
    {
        if ($length>0) {
            $randId = "";
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
            $charactersLength = strlen($characters);
            for ($i = 0; $i < $length; $i++) {
                $randId .= $characters[random_int(0, $charactersLength - 1)];
            }
            return $randId;
        }
        return '';
    }

    function generateUniqueKey($transactionType, $phoneNumber) {
        // TODO: implement system request id
//        $timestamp = date("YmdHis"); // Format: YYYYMMDDHHMMSS
//        $uniqueKey = $transactionType . $phoneNumber . '-' . $timestamp;
//        return $uniqueKey;
//
//        // Example usage:
//        $airtimeDataKey = generateUniqueKey('A', '1234567890');
//        $electricityKey = generateUniqueKey('E', '1234567890');
//
//        echo "Airtime/Data Key: " . $airtimeDataKey . "<br>";
//        echo "Electricity Key: " . $electricityKey . "<br>";
    }
}
