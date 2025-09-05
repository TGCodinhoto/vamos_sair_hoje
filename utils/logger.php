<?php
class Logger {
    private static $logFile = '../logs/php_error.log';

    public static function log($message, $type = 'INFO') {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] [$type] $message" . PHP_EOL;
        error_log($logMessage, 3, self::$logFile);
    }

    public static function info($message) {
        self::log($message, 'INFO');
    }

    public static function error($message) {
        self::log($message, 'ERROR');
    }

    public static function debug($message) {
        if (is_array($message) || is_object($message)) {
            $message = print_r($message, true);
        }
        self::log($message, 'DEBUG');
    }
}
?>
