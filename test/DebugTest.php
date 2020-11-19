<?php

namespace Pendenga\Log\Test;

use DomainException;
use Pendenga\Log\Debug;
use PHPUnit\Framework\TestCase;

include_once __DIR__ . '/../vendor/autoload.php';

class DebugTest extends TestCase
{
    const LOG_FILE = __DIR__ . '/../log.txt';

    public function setup()
    {
        if (file_exists(self::LOG_FILE)) {
            unlink(self::LOG_FILE);
        }
    }

    /**
     * @covers Debug::debug
     * @runInSeparateProcess
     */
    public function testDebug()
    {
        $logger = Debug::instance();
        $logger->debug('something unimportant');
        $this->assertEquals(['|debug|something unimportant []'], $this->logFile());
    }

    /**
     * No logging is done because all messages are below minimum log level
     * @covers Debug::error
     * @covers Debug::debug
     */
    public function testFilter_notLogged()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('log file not found');

        $logger = Debug::instance(Debug::WARNING);
        $logger->debug('something unimportant');
        $this->logFile();
    }

    /**
     * Debug logging is below minimum log level
     * @covers Debug::error
     * @covers Debug::debug
     */
    public function testFilter_debug()
    {
        $logger = Debug::instance(Debug::WARNING);
        $logger->debug('something unimportant');
        $logger->error('something important!');
        $this->assertEquals(['|error|something important! []'], $this->logFile());
    }

    /**
     * All messages written (default)
     * @covers Debug::error
     * @covers Debug::debug
     */
    public function testFilter_all()
    {
        $logger = Debug::instance();
        $logger->debug('something unimportant');
        $logger->error('something important!');
        $this->assertEquals(
            [
                '|debug|something unimportant []',
                '|error|something important! []',
            ],
            $this->logFile()
        );
    }

    /**
     * Retrieve lines from log file as array and strip the timestamps (for easier matching)
     * @return array
     */
    protected function logFile(): array
    {
        if (!file_exists(self::LOG_FILE)) {
            throw new DomainException('log file not found');
        }
        $output = [];
        $log_lines = file(self::LOG_FILE);
        foreach ($log_lines as $line) {
            $output[] = trim(substr($line, 17));
        }

        return $output;
    }
}
