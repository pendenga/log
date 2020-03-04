<?php

use Pendenga\Log\EchoLogger;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;
include_once '../vendor/autoload.php';

class Foo
{
    use LoggerAwareTrait;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->setLogger($logger ?? new NullLogger());
    }

    public function bar() {
        $this->logger->debug(__METHOD__);
        // ...
        $this->logger->error(__METHOD__ . " Something Important!", ['code' => 123]);
    }
}

$foo = new Foo(new EchoLogger());
$foo->bar();
echo "---\n";
$foo->setLogger(new EchoLogger(LogLevel::ERROR));
$foo->bar();
