<?php declare(strict_types=1);

namespace App\classes;

class ErrorHandler extends \Exception
{
    public static function handleError($errno, $errstr, $errfile, $errline): void
    {
        // Log error message and trace using Logger class
        Logger::log('api', "Error (level $errno): $errstr in $errfile on line $errline");
        Logger::log('api', self::getBacktraceAsString());
    }

    public static function handleException($exception): void
    {
        // Log exception message and trace using Logger class
        Logger::log('api', "Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine());
        Logger::log('api', $exception->getTraceAsString());
    }

    private static function getBacktraceAsString(): string
    {
        // Get the backtrace as a string
        return var_export(debug_backtrace(), true);
    }
}