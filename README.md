# Pendenga's PSR Logger for Dev

Use EchoLogger in dev areas to just echo all the log output. I found myself writing this class over and over in 
my dev work, so I thought I'd just standardize it for inclusion as a package.

NOTE: This is not recommended for production use.

## Installation

This package is hosted on packagist and is installable via [Composer][link-composer].

### Requirements

- PHP version 7.1 or greater
- Composer (for installation)

### Installing Via Composer

Run the following: 

```bash
$ composer require-dev pendenga/log
```

Or add the following lines to your composer.json file...

```json
"require-dev": {
  "pendenga/log": "0.1.0",
},
```

and run the following command (assuming `composer` is available in your PATH):

```bash
$ composer update
```

When bootstrapping your application, you will need to require `'vendor/autoload.php'` in order to setup autoloading.


## Usage

### Code

You can choose the minimum log level to start with, if you don't need all the debug output. 
This example tries out two different minimum log levels. 

```php
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
```

Runs with this output: 
```bash
debug: Setting minimum log level: debug []
debug: Foo::bar []
error: Foo::bar Something Important! {"code":123}
---
error: Foo::bar Something Important! {"code":123}
```

[link-composer]: https://getcomposer.org/
