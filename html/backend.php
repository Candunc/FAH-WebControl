<?php
function getSlots($host,$pass,$port) {
	// Returns JSON array of data from the Folding@Home Client
	$str = "";
	if (isset($host)) {
		$str = ($str . escapeshellarg($host) . ' ');
	}
	if (isset($pass)) {
		$str = ($str . escapeshellarg($pass) . ' ');
	}
	if (isset($port)) {
		$str = ($str . escapeshellarg($port) . ' ');
	}
	return json_decode(shell_exec('lua wrapper.lua ' . $str)); //Todo: Absolute path in /opt/
}

function getSensors($url) {
	// HTTP GET to a OpenHardwareMonitor web server; returns the JSON array
	return file_get_contents($url);
}
$configs = include('config.php'); // Contains settings and hosts
$out = array();

foreach ($configs['machines'] as $key => $value) {
	$out[$key] = array('folding' => @getSlots($value['host'],$value['pass'],$value['port'])); //Disable the error for undefined index to make config easier
//	if ($value["sensors"] == true) {
//		$out[$key]["sensors"] = getSensors($value['host']);
//	}
}
echo(json_encode($out)); //Basically let the client deal with the shit for now.
?>
