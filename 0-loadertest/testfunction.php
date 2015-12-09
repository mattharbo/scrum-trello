<?php

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

		if (!empty($output)) {
			$obj = json_decode($output);
			echo "<div id='subtopbar'><center><h2>Dashboard : ".$obj[0]->data->board->name."</h2> Last update at ".date(H.'\h'.i.'\m'.s.'\s')."<br>";
			echo "</center></div>";
		}
}

?>