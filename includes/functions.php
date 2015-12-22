<?php
/*------------------------------------------------------------------------
--> Get Dev API key URL
https://trello.com/app-key
--> Dev API key
65c83fd020db39e2027c509a67587125
--> Get trello token URL
https://trello.com/1/connect?key=65c83fd020db39e2027c509a67587125&name=MyApp&response_type=token
--> Token (expered never)
d8cfaa1f1f58a0e0b7d34befc2952cc26352a3e97c948bd9f80243377f98654e
--> Current sprint board ID
561228dc16f33267799133c3
--> MHA Board
5631f6921f2294f89215a18d
------------------------------------------------------------------------*/

function htmlheader($title, $css){
return <<<HTML
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.0/jquery.min.js"></script>
    <title>{$title}</title>
    <link rel="stylesheet" type="text/css" href="{$css}">
    <script>
        $(window).load(function(){
            $("#loader").show();
        });
	</script>
</head>
<body>
HTML;
}

function topbar($path){
return <<<HTML
<div id="topbar">
	<ul>
  		<li><a href="{$path}./" class="topbarlink">HOME</a></li>
  		<li><a href="{$path}/nextsprintcandidates/" class="topbarlink" target="_blank">NEXT SPRINT CANDIDATES</a></li>
  		<li><a href="{$path}/scrumreport/" class="topbarlink" target="_blank">BURNDOWN CHART</a></li>
	</ul>
	<div class="appversion">v3.2</div>
</div>
HTML;
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

function displaycalendar(){

	// THIS FUNCTION CANNOT GO BACK MORE THAN TWO MONTH

	$dayfetched = date(d);
	$monthfetched = date(m);
	$yearfetched = date(Y);

	// The "< number" determines the days you want to display in past
	for ($i=0; $i < 7 ; $i++) { 
		
		if ($dayfetched-$i > 0){
			echo "<a href='?d=".($dayfetched-$i)."&m=".$monthfetched."&y=".$yearfetched."'>".($dayfetched-$i)." ".jdmonthname(juliantojd($monthfetched,($dayfetched-$i),$yearfetched),2)."</a> • ";
		}
		else{
			$previousmonth = $monthfetched-1;

			if ($previousmonth > 0) {
				$query_date = $yearfetched."-".$previousmonth."01";
				$numberofdaysinpreviousmonth = date('t', strtotime($query_date));
				echo "<a href='?d=".($numberofdaysinpreviousmonth+($dayfetched-$i))."&m=".$previousmonth."&y=".$yearfetched."'>".($numberofdaysinpreviousmonth+($dayfetched-$i))." ".jdmonthname(juliantojd($previousmonth,($numberofdaysinpreviousmonth+($dayfetched-$i)),$yearfetched),2)."</a> •";
			}else{
				echo "Previous year...";
			}
		}
	}
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

// Date Trello format -> "2015-11-16T16:16:30.521Z"

function datetreatmentlight($inputdate){

	$firsthyphen = strpos($inputdate, "-")+1;
	$secondhyphen = strpos($inputdate, "-", $firsthyphen)+1;
	$firstdots = strpos($inputdate, ":");
	$secondsdots = strpos($inputdate, ":", $firstdots)+4;
	
	$yyyy=substr($inputdate, 2, 2);
	$mm=substr($inputdate, $firsthyphen,2);
	$dd=substr($inputdate, $secondhyphen,2);
	$hh=substr($inputdate, -$firstdots,2)+1;
	$mn=substr($inputdate, $firstdots+1,2);
	$ss=substr($inputdate, $secondsdots,2);
	
	return $dd."/".$mm;
}

function pickurldate(){

	// ?d=13&m=11&y=2015

	if (!empty($_GET['d'])) {
		$dayurl = $_GET['d'];
		$monthurl = $_GET['m'];
		$yearurl = $_GET['y'];
	}else{
		$dayurl = date(d);
		$monthurl = date(m);
		$yearurl = date(Y);
	}

	return array($dayurl, $monthurl, $yearurl);
}

function findadate($inputdate, $comparedday, $comparedmonth, $comparedyear){

	// Get hyphen position
	$firsthyphen = strpos($inputdate, "-")+1;
	$secondhyphen = strpos($inputdate, "-", $firsthyphen)+1;

	// Get day, month and year of the input date
	$yyyy=substr($inputdate, 0, 4);
	$mm=substr($inputdate, $firsthyphen,2);
	$dd=substr($inputdate, $secondhyphen,2);

	if ($yyyy == $comparedyear){
		if ($mm == $comparedmonth) {
			if ($dd == $comparedday) {
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}else{
		return false;
	}
}

function fetchcomplexity($inputcard){

	$openingbracket = strpos($inputcard, "(");
	$closingbracket = strpos($inputcard, ")");
	$lenghtcomplexity = $closingbracket - $openingbracket;

	if($lenghtcomplexity > 1){
		$complexity=substr($inputcard, $openingbracket+1, $lenghtcomplexity-1);
		if (is_numeric($complexity)) {
		        return $complexity;
		    } else {
		        return null;
		    }	
	}else{
		return null;
	}
	
}

function cardsmemberurl($memberid){

	$urltocall = "https://api.trello.com/1/boards/561228dc16f33267799133c3/members/".$memberid."/cards?fields=name,dateLastActivity,shortUrl&members=true&member_fields=avatarHash,fullName,username,initials&list=true&list_fields=name&=false&attachments=true&attachment_fields=url&key=65c83fd020db39e2027c509a67587125&token=d8cfaa1f1f58a0e0b7d34befc2952cc26352a3e97c948bd9f80243377f98654e";
	return $urltocall;
}

function calcomplexity($maxtotcomp, $numberofdays, $dayfocused, $holifayspassed){
	
	$compneededperday=$maxtotcomp/$numberofdays;	
	$compgivenday=$maxtotcomp-($compneededperday*$dayfocused)+($holifayspassed*$compneededperday);
	
	return $compgivenday;
}

function file_existance($folderandfilepath){

	if (!file_exists("./".$folderandfilepath."/".$folderandfilepath.".json")){
		echo "<br><br><br><br><br>".$folderandfilepath." file is missing into folder.";
	}
	
}









?>