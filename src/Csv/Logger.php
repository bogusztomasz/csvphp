<?php

declare(strict_types=1);

namespace Csv;

use Exception;

final class Logger
{

    private static ?Logger $instance = null;

    private string $file = '';

    public static function getInstance(): Logger
    {

        if (!is_a(self::$instance, self::class)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public function log(string $message)
    {
        if (!file_exists($this->file)) {
            file_put_contents($this->file, microtime(true) . ': Log file created' . PHP_EOL);
        }
        
        file_put_contents($this->file, microtime(true) . ': ' . $message . PHP_EOL, FILE_APPEND);
    }

    private function __construct()
    {
        $this->file = getcwd() . '/log_' . date('Y-m-d') . '.log';
    }

    public function __wakeup()
    {
        throw new Exception('Cannot unserialize ' . self::class);
    }

    private function __clone()
    {
        throw new Exception('Cannot clone ' . self::class);
    }
}
