## Security

Advanced Security Class for Php

[![Latest Stable Version](http://poser.pugx.org/omerfdmrl/logger/v)](https://packagist.org/packages/omerfdmrl/logger) 
[![Total Downloads](http://poser.pugx.org/omerfdmrl/logger/downloads)](https://packagist.org/packages/omerfdmrl/logger) 
[![Latest Unstable Version](http://poser.pugx.org/omerfdmrl/logger/v/unstable)](https://packagist.org/packages/omerfdmrl/logger) 
[![License](http://poser.pugx.org/omerfdmrl/logger/license)](https://packagist.org/packages/omerfdmrl/logger) 
[![PHP Version Require](http://poser.pugx.org/omerfdmrl/logger/require/php)](https://packagist.org/packages/omerfdmrl/logger)

### Features
- Save User IP Address, Device, Platform, Platform Version, Browser, Browser Version, Log Title, Log Message, Log Date, Log Type
- Create Table Automaticly
- Delete, Turncate Table
- Pdo and Mysqli supported
- Get Error

## Install

run the following command directly.

```
$ composer require omerfdmrl/logger
```

## Settings - PDO
```php
include 'vendor/autoload.php';

use Omerfdmrl\Logger\Logger;

$logger = new Logger;

// PDO Connection
$db = new PDO("mysql:host=localhost;dbname=dbname", "db_user", "db_pass");

$logger->db($db,'pdo',True);
// First: PDO connection veriable
// Second: Connection type (Default is pdo. If using pdo, you may not write)
// Third: Create table. If table exist, set as False. Default is True
```

## Settings - Mysqli
```php
include 'vendor/autoload.php';

use Omerfdmrl\Logger\Logger;

$logger = new Logger;

// Mysqli Connection
$db = mysqli_connect('localhost', 'db_user', 'db_pass', 'dbname');

$logger->db($db,'mysqli',True);
// First: Mysqli connection veriable
// Second: Connection type (Default is pdo. If using pdo, you may not write)
// Third: Create table. If table exist, set as False. Default is True
```

## Usage
```php
// Save Log
$logger->save('title','message',$user_id,$type);
// First: Log title
// Second: Log message
// Third: User id for spesific user. Default is 0
// Fourth: Log type. Default is 0

// Drop Table
$logger->drop();

// Turncate Table
$logger->truncate();
```

## Docs
Documentation page: [Security Docs][doc-url]


## Licence
[MIT Licence][mit-url]

## Contributing

1. Fork it ( https://github.com/omerfdmrl/logger/fork )
2. Create your feature branch (git checkout -b my-new-feature)
3. Commit your changes (git commit -am 'Add some feature')
4. Push to the branch (git push origin my-new-feature)
5. Create a new Pull Request

## Contributors

- [omerfdmrl](https://github.com/omerfdmrl) Ömer Faruk Demirel - creator, maintainer

[mit-url]: http://opensource.org/licenses/MIT
[doc-url]: https://github.com/omerfdmrl/logger/wiki
