<?php

namespace Pendenga\Log;

use Psr\Log\AbstractLogger;

class Debug extends AbstractLogger
{
    const EMERGENCY = 7;
    const ALERT = 6;
    const CRITICAL = 5;
    const ERROR = 4;
    const WARNING = 3;
    const NOTICE = 2;
    const INFO = 1;
    const DEBUG = 0;
    const MAP = [
        'emergency' => self::EMERGENCY,
        'alert'     => self::ALERT,
        'critical'  => self::CRITICAL,
        'error'     => self::ERROR,
        'warning'   => self::WARNING,
        'notice'    => self::NOTICE,
        'info'      => self::INFO,
        'debug'     => self::DEBUG,
    ];

    protected $min_level;

    public function __construct(int $min_level)
    {
        $this->min_level = $min_level;
    }

    /**
     * @return self
     */
    public static function instance(int $min_level = self::DEBUG): self
    {
        return new static($min_level);
    }

    /**
     * @inheritDoc
     */
    public function log($level, $message, array $context = [])
    {
        if ($this->min_level <= self::MAP[$level]) {
            $fh = fopen(__DIR__ . '/../log.txt', 'a+');
            $log_entry = [
                date('Ymd H:i:s'),
                $level,
                $message . ' ' . json_encode($context),
            ];

            fwrite($fh, implode('|', $log_entry) . "\n");
            fclose($fh);
        }
    }
}
