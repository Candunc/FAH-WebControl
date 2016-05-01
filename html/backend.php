<?php
// From http://stackoverflow.com/questions/3258634/php-how-to-send-http-response-code
// For 4.3.0 <= PHP <= 5.4.0
if (!function_exists('http_response_code'))
{
    function http_response_code($newcode = NULL)
    {
        static $code = 200;
        if($newcode !== NULL)
        {
            header('X-PHP-Response-Code: '.$newcode, true, $newcode);
            if(!headers_sent())
                $code = $newcode;
        }       
        return $code;
    }
}

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
	return json_decode(shell_exec('lua /opt/wrapper.lua ' . $str)); //Todo: Verify file with sha256sum? Maybe ensure that it exists, if not compile.
}

function getSensors($url) {
	// HTTP GET to a OpenHardwareMonitor web server; returns the JSON array
	return file_get_contents($url);
}
$configs = include('config.php'); // Contains settings and hosts
$out = array();

if (isset($_GET['q'])) {
	if ($_GET['q'] == "fold_stats") {
		foreach ($configs['machines'] as $key => $value) {
			$out[$key] = array('folding' => @getSlots($value['host'],$value['pass'],$value['port'])); //Disable the error for undefined index to make config easier
		//	if ($value["sensors"] == true) {
		//		$out[$key]["sensors"] = getSensors($value['host']);
		//	}
		}
		echo(json_encode($out)); //Basically let the client deal with the shit for now.
	} else {
		http_response_code(501);
		echo("Error 501: Bad Request: No known command");
	}
} else {
	http_response_code(400);
	echo("Error 400: Bad Request: No paramater specified in $_GET['q']");
}
?>
