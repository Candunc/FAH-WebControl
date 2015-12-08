<?php
require_once("Telnet.php");
require_once("Telnet_Exception.php");

$host = array('10.215.3.81' => 'name',36330 => 'port','ab123ab123' => 'pass'); // This is an _example_ password... please don't actually use it.

$telnet = new Parsonline_Network_Telnet($host['name']); //Currently hardcoded, move to a config.php array or something like that
$telnet->setPort($host['port']);
$telnet->setConnectionTimeout(60); //This should be more than 5 seconds, not sure what the _maximum_ should be, however
$telnet->setPrompt(">");

try {
    $telnet->connect();
    $telnet->write("auth " . $host['pass']);
    if ($telnet->waitForPrompt() == "OK\n") {
    	$telnet->write("slot-info");
    	$response = $telnet->waitForPrompt();
    	$output = json_decode(substr($response, 12, -4));
    } else {
    	die("Wrong password!");
    }
    $telnet->disconnect();
} catch (Parsonline_Network_Telnet_Exception $e) {
    // handle connection or IO error
    $telnet->disconnect();
    die("Error occurred: " . $e);
}