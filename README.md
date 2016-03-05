# Contao Tivoka [![Build Status](https://travis-ci.org/BugBuster1701/contao-tivoka.svg)](https://travis-ci.org/BugBuster1701/contao-tivoka)
[JSON-RPC](http://jsonrpc.org/) client and server for PHP 5.3+

Based on [tivoka](https://github.com/marcelklehr/tivoka) in version 3.4.0

* JSON-RPC client/server library for PHP (supports v1.0 and v2.0 specs)
* Easily switch between the [v1.0](http://json-rpc.org/wiki/specification) and [v2.0](http://jsonrpc.org/specification) specs
* HTTP, TCP and Websocket transports available
* New: CurlHTTP available, used if HTTP not allowed (allow_url_fopen)


## Examples ##

These are just some quick examples. Check out the docs in [`/doc/`](https://github.com/BugBuster1701/contao-tivoka/tree/master/doc).

Do a request through HTTP...
```php
<?php
$connection = BugBuster\Tivoka\Client::connect('http://example.com/api')
$request = $connection->sendRequest('substract', array(51, 9));
print $request->result;// 42
?>
```

...or plain TCP
```php
<?php
$connection = BugBuster\Tivoka\Client::connect(array('host' => 'example.com', 'port' => 1234))
$request = $connection->sendRequest('substract', array(51, 9));
print $request->result;// 42
?>
```

...or WebSocket
```php
<?php
$connection = BugBuster\Tivoka\Client::connect('ws://example.com/api')
$request = $connection->sendRequest('substract', array(51, 9));
print $request->result;// 42
?>
```

Create a server
```php
<?php
$methods = array(
    'substract' => function($params) {
        list($num1, $num2) = $params
        return $num1 - $num2;
    }
);
BugBuster\Tivoka\Server::provide($methods)->dispatch();
?>
```

## Installation

### Install composer package

1. Set up `composer.json` in your project directory:
```
{
  "require":{"bugbuster/tivoka":"*"}
}
```

2. Run [composer](http://getcomposer.org/doc/00-intro.md#installation):
```sh
$ php composer.phar install
```

Now, `include 'vendor/autoload.php'`

## License ##
Copyright 2011-2012 by Marcel Klehr, MIT License.

Copyright (c) 2014-2016 Glen Langer (Contao Version), MIT License.
