<?php

namespace Pendenga\Log;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

/**
 * Class EchoLogger
 * @package Pendenga\Log
 */
class EchoLogger extends AbstractLogger
{
    const LEVELS = [
        LogLevel::DEBUG,
        LogLevel::INFO,
        LogLevel::NOTICE,
        LogLevel::WARNING,
        LogLevel::ERROR,
        LogLevel::CRITICAL,
        LogLevel::ALERT,
        LogLevel::EMERGENCY,
    ];

    protected $min_log_level;

    /**
     * EchoLogger constructor.
     * @param string|int|null $min_log_level
     */
    public function __construct($min_log_level = LogLevel::DEBUG)
    {
        // set level for logging, this level and above
        if (is_numeric($min_log_level) && isset(self::LEVELS[$min_log_level])) {
            $this->min_log_level = self::LEVELS[$min_log_level];
        } elseif (in_array($min_log_level, self::LEVELS)) {
            $this->min_log_level = $min_log_level;
        } else {
            $this->min_log_level = LogLevel::DEBUG;
        }
        $this->log(LogLevel::DEBUG, 'Setting minimum log level: ' . LogLevel::DEBUG);
    }

    /**
     * @param $level
     * @return bool
     */
    protected function validateLogLevel($level)
    {
        return array_search($level, self::LEVELS) >= array_search($this->min_log_level, self::LEVELS);
    }

    /**
     * @inheritDoc
     */
    public function log($level, $message, array $context = array())
    {
        if ($this->validateLogLevel($level)) {
            echo "{$level}: {$message} " . json_encode($context) . "\n";
        }
    }
}
