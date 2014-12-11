# Tivoka [![Build Status](https://travis-ci.org/BugBuster1701/tivoka.svg)](https://travis-ci.org/BugBuster1701/tivoka)
[JSON-RPC](http://jsonrpc.org/) client and server for PHP 5.3+, special version for Contao CMS

Based on [tivoka](https://github.com/marcelklehr/tivoka) in version 3.4.0

* Easily switch between the [v1.0](http://json-rpc.org/wiki/specification) and [v2.0](http://jsonrpc.org/specification) specs
* HTTP, TCP and Websocket transports available
* New: CurlHTTP available, used if HTTP not allowed (allow_url_fopen)

## Examples ##
These are just some quick examples. Check out the docs in [`/doc/`](https://github.com/BugBuster1701/contao-tivoka/tree/contao-develop/doc).

Do a request through HTTP...
```php
<?php
$connection = Tivoka\Client::connect('http://example.com/api')
$request = $connection->sendRequest('substract', array(51, 9));
print $request->result;// 42
?>
```

...or plain TCP
```php
<?php
$connection = Tivoka\Client::connect(array('host' => 'example.com', 'port' => 1234))
$request = $connection->sendRequest('substract', array(51, 9));
print $request->result;// 42
?>
```

...or WebSocket
```php
<?php
$connection = Tivoka\Client::connect('ws://example.com/api')
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
Tivoka\Server::provide($methods)->dispatch();
?>
```

## Installation

### Install composer package (in preperation)
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
Copyright 2011-2012 by Marcel Klehr
MIT License.

