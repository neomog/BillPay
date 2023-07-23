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
}
