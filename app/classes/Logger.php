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
}
