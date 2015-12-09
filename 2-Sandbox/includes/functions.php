<?php

function htmlheader($title, $css){
return <<<HTML
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{$title}</title>
    <link rel="stylesheet" type="text/css" href="{$css}">
</head>
<body>
HTML;
}

function appversion() {
	echo "<b>v1.1.6</b><br><br><br>";
}

function fetchdataonapi($url){
		# init curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $encoded_fields);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		// curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true); // make sure we see the sended header afterwards
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		//curl_setopt($ch, CURLOPT_POST, 1);

		# dont care about ssl
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		# download and close
		$output = curl_exec($ch);
		// $request =  curl_getinfo($ch, CURLINFO_HEADER_OUT);
		// $error = curl_error($ch);
		curl_close($ch);
		return $output;
}

function datetreatment($inputdate){

	$firsthyphen = strpos($inputdate, "-")+1;
	$secondhyphen = strpos($inputdate, "-", $firsthyphen)+1;
	$firstdots = strpos($inputdate, ":");
	$secondsdots = strpos($inputdate, ":", $firstdots)+4;
	
	$yyyy=substr($inputdate, 0, 4);
	$mm=substr($inputdate, $firsthyphen,2);
	$dd=substr($inputdate, $secondhyphen,2);
	$hh=substr($inputdate, -$firstdots,2)+1;
	$mn=substr($inputdate, $firstdots+1,2);
	$ss=substr($inputdate, $secondsdots,2);
	
	return $yyyy."-".$mm."-".$dd." • ".$hh.":".$mn.":".$ss;
}

?>