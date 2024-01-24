<?php declare(strict_types=1);

namespace App\classes;

class Logger
{
    protected $logFilePath;

    public function __construct(string $logDirectory = 'logs')
    {
        // Create the logs directory if it doesn't exist
        if (!file_exists($logDirectory) || !is_dir($logDirectory)) {
            mkdir($logDirectory, 0777, true);
        }

        // Set the log file path
        $this->logFilePath = $logDirectory . DIRECTORY_SEPARATOR . 'error_log.txt';
    }

    public function logError(string $errorMessage): void
    {
        $formattedError = date('[Y-m-d H:i:s]') . ' ' . $errorMessage . PHP_EOL;
        file_put_contents($this->logFilePath, $formattedError, FILE_APPEND | LOCK_EX);
    }

    public static function log($filename, $txt_data): void
    {
        $path = dirname(__DIR__, 2) . '/logs';
        $directoryName = $path . '/' . date('m-d-Y') . "/";

        if (is_object($txt_data)) {
            $txt_data = json_decode(json_encode($txt_data), true);
        }

        if (is_array($txt_data)) {
            $txt_data = json_encode($txt_data);
        }

        if (!is_dir($path)) {
            mkdir($path, 0755);
        }

        if (!is_dir($directoryName)) {
            mkdir($directoryName, 0755);
        }

        $filePath = $directoryName . $filename . ".txt";
        file_put_contents($filePath, $txt_data . "\r\n", FILE_APPEND);
    }

}
