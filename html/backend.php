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

function fileToArray($file) {
	//Helper function to simplify some code.
	if (file_exists('cache/' . $file)) {
		return json_decode(file_get_contents('cache/' . $file));
	} else {
		return array();
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
			$out[$key] = array('folding' => @getSlots($value['host'],$value['pass'],$value['port'])); //Disable the notice for "undefined index" to make config easier
		//	if ($value["sensors"] == true) {
		//		$out[$key]["sensors"] = getSensors($value['host']);
		//	}
		}
		echo(json_encode($out)); //Basically let the client deal with the shit for now.
	} elseif ($_GET['q'] == 'balance') {
		$out = array();
		//Limited to one address per wallet.
		if (isSet($config['wallet']['curecoin'])) { //If there was a unified blockchain, it might be worth interating through in a loop, however with so many different APIs with different restrictions, I don't know if it'll be worth it with two coins.
			$filename = md5($config['wallet']['curecoin']); // Speed > collisions
			$input = fileToArray($filename); 
			if (isset($input['timestamp']) and (time() - $input['timestamp'] <= 3600)) { //Only call API if file doesn't exist OR file is more than 1 hour old.
				$out['curecoin'] = $input['data'];

			} else {
				$data = file_get_contents('http://chainz.cryptoid.info/cure/api.dws?q=getbalance&a=' . $config['wallet']['curecoin']); //Todo: This API supports a key to speed things up. Integrate that into the config?
				file_put_contents('cache/' . $filename, json_encode(array('timestamp' => time(), 'data' => $data)));
				$out['curecoin'] = $data;
			}

			//Todo: Integrate with some sort of selenium API to grab coins from the pool
		}
		if (isSet($config['wallet']['bitcoin'])) {
			$filename = md5($config['wallet']['bitcoin']);
			$input = fileToArray($filename); 
			if (isset($input['timestamp']) and (time() - $input['timestamp'] <= 600)) { //Bitcoin addresses are likely to change more than a bitcoin address, I'd assume
				$out['bitcoin'] = $input['data'];
			} else {
				$data = (file_get_contents('https://blockchain.info/q/addressbalance/' . $config['wallet']['bitcoin'])/100000000); //Satochi conversion
				file_put_contents($filename, json_encode(array('timestamp' => time(), 'data' => $data)));
				$out['bitcoin'] = $data;
			}
		}

		echo(json_encode($out));
	} else {
		http_response_code(400);
		echo("Error 400: Bad Request: No known command or missing parameters.");
	}
} else {
	http_response_code(400);
	echo("Error 400: Bad Request: No paramater specified in $_GET['q']");
}
?>
