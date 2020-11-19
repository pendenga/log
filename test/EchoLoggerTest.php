<?php

namespace Pendenga\Log\Test;

use DomainException;
use Pendenga\Log\EchoLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

include_once __DIR__ . '/../vendor/autoload.php';

class EchoLoggerTest extends TestCase
{
    public function setup()
    {
        ob_start();
    }

    public function tearDown()
    {
        ob_end_clean();
    }

    /**
     * @covers EchoLogger::debug
     * @runInSeparateProcess
     */
    public function testDebug()
    {
        $logger = new EchoLogger();
        $logger->debug('something unimportant');
        $this->assertEquals(['debug: something unimportant []'], $this->logFile());
    }

    /**
     * No logging is done because all messages are below minimum log level
     * @covers EchoLogger::error
     * @covers EchoLogger::debug
     */
    public function testFilter_notLogged()
    {
        $logger = new EchoLogger(LogLevel::WARNING);
        $logger->debug('something unimportant');
        $this->assertEquals(1, count($this->logFile()));
    }

    /**
     * Debug logging is below minimum log level
     * @covers EchoLogger::error
     * @covers EchoLogger::debug
     */
    public function testFilter_debug()
    {
        $logger = new EchoLogger(LogLevel::WARNING);
        $logger->debug('something unimportant');
        $logger->error('something important!');
        $this->assertEquals(['error: something important! []'], $this->logFile());
    }

    /**
     * All messages written (default)
     * @covers EchoLogger::error
     * @covers EchoLogger::debug
     */
    public function testFilter_all()
    {
        $logger = new EchoLogger();
        $logger->debug('something unimportant');
        $logger->error('something important!');
        $this->assertEquals(
            [
                'debug: something unimportant []',
                'error: something important! []',
            ],
            $this->logFile()
        );
    }

    /**
     * @return array
     */
    protected function logFile(): array
    {
        $contents = explode("\n", trim(ob_get_contents()));

        if (count($contents) < 1) {
            throw new DomainException('log output not found');
        }

        return $contents;
    }
}
