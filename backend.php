<?php
function getSlots(host,pass,port) {
	$str = ""
	if isset($host) {
		$str = ($str . escapeshellarg($host));
	}
	if isset($pass) {
		$str = ($str . escapeshellarg($pass));
	}
	if isset($port) {
		$str = ($str . escapeshellarg($port);
	}
	return exec('lua wrapper.lua ' . $str);
}

require("config.php"); // Contains settings and hosts

?>
