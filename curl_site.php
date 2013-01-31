<?php
// Turn error reporting on when on localhost
if (preg_match("/localhost/i", $_SERVER['SERVER_NAME'])) {
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
} 

// This is where that magic happens
function site_curl($url) {
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:18.0) Gecko/20100101 Firefox/18.0");
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15); // The amount of seconds to wait for host to respond
	curl_setopt($ch, CURLOPT_TIMEOUT, 30); // The amount of seconds to allow this oparation to run for - by default it never times out
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$results = curl_exec($ch);
	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	curl_close($ch);

	if ($status == '200') {
		return $results;
	} else {
		throw new Exception("Error Processing The cURL Request", 1);
	}
}

function scriptExtract($html) {
	if (!empty($html)) {
		// Lets create a new Document object out of the html
		$doc = new DOMDocument();

		// The '@' gets rid of annoying warnings in regards to a valid HTML document. 
		// We don't care if it's a valid document, we just want some html section.
		@$doc->loadHTML($html); 

		// Get the script tags in the head
		$head_scripts = ''; // We're gonna make a string out of what we find
		$scripts = $doc->documentElement->getElementsByTagName('head')->item(0)->getElementsByTagName('script');

		// Grab the value between the opening (<script>Some value here...</script>) and closing script tags
		// We're not dealing with src scripts here
		foreach ($scripts as $script) {
			$head_scripts .= $script->nodeValue;
		}

		// Let's work with JSON
		if (!empty($head_scripts)) {
			return json_encode(array(
				'head' => array(
					'scripts' => trim(htmlspecialchars($head_scripts, ENT_QUOTES, 'UTF-8'))
				)
			));	
		}
	}
}

if (isset($_GET['site']) && !empty($_GET['site'])) {
	$html = site_curl(htmlspecialchars($_GET['site'], ENT_QUOTES, 'UTF-8'));
	$json = scriptExtract($html);

	if ($json) {
		echo $json;
	}
}