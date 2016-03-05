<pre>
<?php
include('../include.php'); //only for example! This Contao extension uses the autoloader.

$request = BugBuster\Tivoka\Client::request('demo.substract', array(43,1));
$greeting = BugBuster\Tivoka\Client::request('demo.sayHello');

$target = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].dirname($_SERVER['SCRIPT_NAME']).'/server.php';
BugBuster\Tivoka\Client::connect($target)->send($request, $greeting);


/*
 * Display the Results...
 */

if($request->isError()) var_dump($request->errorMessage);
else var_dump($request->result);// the result

	
if($greeting->isError()) var_dump($greeting->errorMessage);
else var_dump($greeting->result);// the result

?>
</pre>