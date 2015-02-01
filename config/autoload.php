<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Tivoka
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'BugBuster',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'BugBuster\Tivoka\Runtime'                               => 'system/modules/tivoka/classes/Runtime.php',

	// Lib
	'BugBuster\Tivoka\Client\BatchRequest'                   => 'system/modules/tivoka/lib/Tivoka/Client/BatchRequest.php',
	'BugBuster\Tivoka\Client\Connection\AbstractConnection'  => 'system/modules/tivoka/lib/Tivoka/Client/Connection/AbstractConnection.php',
	'BugBuster\Tivoka\Client\Connection\ConnectionInterface' => 'system/modules/tivoka/lib/Tivoka/Client/Connection/ConnectionInterface.php',
	'BugBuster\Tivoka\Client\Connection\Http'                => 'system/modules/tivoka/lib/Tivoka/Client/Connection/Http.php',
	'BugBuster\Tivoka\Client\Connection\Tcp'                 => 'system/modules/tivoka/lib/Tivoka/Client/Connection/Tcp.php',
	'BugBuster\Tivoka\Client\Connection\WebSocket'           => 'system/modules/tivoka/lib/Tivoka/Client/Connection/WebSocket.php',
	'BugBuster\Tivoka\Client\Connection\CurlHttp'            => 'system/modules/tivoka/lib/Tivoka/Client/Connection/CurlHttp.php',
	'BugBuster\Tivoka\Client\NativeInterface'                => 'system/modules/tivoka/lib/Tivoka/Client/NativeInterface.php',
	'BugBuster\Tivoka\Client\Notification'                   => 'system/modules/tivoka/lib/Tivoka/Client/Notification.php',
	'BugBuster\Tivoka\Client\Request'                        => 'system/modules/tivoka/lib/Tivoka/Client/Request.php',
	'BugBuster\Tivoka\Client'                                => 'system/modules/tivoka/lib/Tivoka/Client.php',
	'BugBuster\Tivoka\Exception\ConnectionException'         => 'system/modules/tivoka/lib/Tivoka/Exception/ConnectionException.php',
	'BugBuster\Tivoka\Exception\Exception'                   => 'system/modules/tivoka/lib/Tivoka/Exception/Exception.php',
	'BugBuster\Tivoka\Exception\InvalidParamsException'      => 'system/modules/tivoka/lib/Tivoka/Exception/InvalidParamsException.php',
	'BugBuster\Tivoka\Exception\ProcedureException'          => 'system/modules/tivoka/lib/Tivoka/Exception/ProcedureException.php',
	'BugBuster\Tivoka\Exception\RemoteProcedureException'    => 'system/modules/tivoka/lib/Tivoka/Exception/RemoteProcedureException.php',
	'BugBuster\Tivoka\Exception\SpecException'               => 'system/modules/tivoka/lib/Tivoka/Exception/SpecException.php',
	'BugBuster\Tivoka\Exception\SyntaxException'             => 'system/modules/tivoka/lib/Tivoka/Exception/SyntaxException.php',
	'BugBuster\Tivoka\Server\MethodWrapper'                  => 'system/modules/tivoka/lib/Tivoka/Server/MethodWrapper.php',
	'BugBuster\Tivoka\Server\Server'                         => 'system/modules/tivoka/lib/Tivoka/Server/Server.php',
	'BugBuster\Tivoka\Server'                                => 'system/modules/tivoka/lib/Tivoka/Server.php',
	'BugBuster\Tivoka\Tivoka'                                => 'system/modules/tivoka/lib/Tivoka/Tivoka.php',
));
