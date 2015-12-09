<?
header("Refresh:3600");//check history every 60 minutes
include '../includes/functions.php';

echo htmlheader("Follo • Burndown chart", "../includes/main.css");
echo topbar("..");
?>
<!-- amCharts javascript sources -->
		<script type="text/javascript" src="http://www.amcharts.com/lib/3/amcharts.js"></script>
		<script type="text/javascript" src="http://www.amcharts.com/lib/3/serial.js"></script>
		<script type="text/javascript" src="http://www.amcharts.com/lib/3/themes/light.js"></script>
<?php

// $allboardcards = "https://api.trello.com/1/boards/561228dc16f33267799133c3/cards?fields=name,idList&key=65c83fd020db39e2027c509a67587125&token=d8cfaa1f1f58a0e0b7d34befc2952cc26352a3e97c948bd9f80243377f98654e";
$boardlistsandcards = "https://api.trello.com/1/boards/561228dc16f33267799133c3/lists?cards=open&card_fields=name&fields=name&key=65c83fd020db39e2027c509a67587125&token=d8cfaa1f1f58a0e0b7d34befc2952cc26352a3e97c948bd9f80243377f98654e";

$newfoldername = date(Y.'\-'.m.'\-'.d);
$filename = './'.$newfoldername.'/';

//IF FOLDER is not existing THEN CREATE IT
if (!file_exists($filename)) {
	?>
	<!-- TO BE REMOVED WHEN CSS DONE -->
	<br><br><br><br>
	<!-- ## -->
	<h5>Sprint dates have not been set :</h5>
	<form method='POST' action='./form.php'>
		<input type='text' name='begindate' placeholder='yyyy-mm-dd'>
		<input type='text' name='enddate' placeholder='yyyy-mm-dd'>
		<input type='submit' value='Submit'>
	</form>
	
	<?
}else{

	//File deletion
	unlink("./".$newfoldername."/".$newfoldername.'.json');
	#File creation into folder
	file_put_contents("./".$newfoldername."/".$newfoldername.'.json', fetchdataonapi($boardlistsandcards)); 
	//WHEN ALL DATA HAVE BEEN CATCHED FROM TRELLO DO THE FOLLOWING

	$graphdata = array('date' => array(), 'totpointrem' => array(), 'totpointboard' =>array(), 'pointdone' =>array());

	$directory = './';
	$filesindir = scandir($directory, 1);
	//print_r($filesindir);

	$index=0;
	$workingdaycounter=0;

	foreach ($filesindir as $key => $foldername) {
		
		if (strpos($foldername,'2015') !== false) {
			$index = $index +1;

			//IN CASE OF SATURDAY OR SUNDAY => NOT PART OF PRODUCTION PLANNING (used in graph edition)
			if (date('w', strtotime($foldername)) != 6) {
				if (date('w', strtotime($foldername)) != 0) {
					$workingdaycounter=$workingdaycounter+1;
				}
			}
			// Sun => 0
			// Mon => 1
			// Tue => 2
			// Wed => 3
			// Thu => 4
			// Fri => 5
			// Sat => 6

	 		//insert date into graphdata array
	 		$graphdata['date'][$index]=$foldername;
			
			$dailyjson = "./".$foldername."/".$foldername.".json";

			if (!empty($dailyjson)) {

				//Fetch data from local JSON file
				$dailyjsoncontent = file_get_contents($dailyjson);
				//Decode JSON response
				$obj = json_decode($dailyjsoncontent);
				//Read each list details
				$boardcomplexity = 0;
				$complexity4scrum = 0;
				

				foreach ($obj as $lists) {

					if ($lists->name == "To do back" OR $lists->name == "To do front" OR $lists->name == "In Progress" OR $lists->name == "To Review") {
						
						// echo $lists->name." : ";

				 		//Read each card details from the list been watched
				 		$listcomplexity = 0;
				 		
				 		foreach ($lists->cards as $cardsoflist) {
				 			$listcomplexity = $listcomplexity + fetchcomplexity($cardsoflist->name);
				 		}

				 		$complexity4scrum = $complexity4scrum + $listcomplexity;
				 	
			 			$boardcomplexity = $boardcomplexity + $listcomplexity;
			 			// echo $listcomplexity."<br>";
					}

					else{

						// echo $lists->name." : ";

				 		//Read each card details from the list been watched
				 		$donecomplexity=0;
				 		$listcomplexity = 0;
				 		
				 		foreach ($lists->cards as $cardsoflist) {
				 			$listcomplexity = $listcomplexity + fetchcomplexity($cardsoflist->name);
				 		}
				 	
			 			$boardcomplexity = $boardcomplexity + $listcomplexity;
			 			// echo $listcomplexity."<br>";

			 			
			 			if ($lists->name == "Done") {
			 				foreach ($lists->cards as $cardsoflists) {
				 				$donecomplexity=$donecomplexity+1;
				 			}

				 			//insert board done complexity into graphdata array
	 						$graphdata['pointdone'][$index]=$donecomplexity;				 			
			 			}
					}	
		 		}
		 	}

			//insert board complexity into graphdata array
		 	if ($complexity4scrum == 0) {
		 		$graphdata['totpointrem'][$index]='null';
		 		$graphdata['pointdone'][$index]='null';
		 	}else{
		 		$graphdata['totpointrem'][$index]=$complexity4scrum;
		 	}

		 	//insert board complexity into graphdata array
	 		$graphdata['totpointboard'][$index]=$boardcomplexity;

	 			
		}
	}

	$livetotsprintcomp=max($graphdata['totpointboard']);
	$holidays = $index-$workingdaycounter;

	// echo $livetotsprintcomp."<br>";
	// echo $workingdaycounter."<br>";
	// echo $index."<br>";
	// print_r($graphdata);

	?>
	<!-- amCharts javascript code -->
			<script type="text/javascript">
				AmCharts.makeChart("chartdiv",
					{
						"type": "serial",
						"categoryField": "category",
						"sequencedAnimation": false,
						"startDuration": 1,
						"startEffect": "easeOutSine",
						"theme": "light",
						"categoryAxis": {},
						"autoRotateAngle": 0,
						"gridPosition": "start",
						"position": "right",
						"trendLines": [],
						"graphs": [
							{
								"balloonText": "[[title]] of [[category]]:[[value]]",
								"fillAlphas": 1,
								"id": "AmGraph-1",
								"labelText": "[[value]]",
								"title": "Done (Daily Closed tickets)",
								"type": "column",
								"valueField": "Done"
							},
							{
								"balloonText": "[[title]] of [[category]]:[[value]]",
								"bullet": "round",
								"id": "AmGraph-2",
								"labelText": "[[value]]",
								"lineThickness": 2,
								"title": "Remaining Complexity",
								"valueField": "CompRem"
							},
							{
								"fillColors": "#FF0000",
								"gapPeriod": 4,
								"id": "AmGraph-4",
								// "labelText": "[[value]]",
								"lineColor": "#FF0000",
								"lineThickness": 2,
								"title": "Projection",
								"valueField": "Projection"
							}
						],
						"guides": [],
						"valueAxes": [
							{
								"id": "ValueAxis-1",
								"title": "Complexity"
							}
						],
						"allLabels": [],
						"balloon": {},
						"legend": {
							"enabled": true,
							"useGraphSettings": true
						},
						"titles": [
							{
								"alpha": 0,
								"color": "#000000",
								"id": "Title-1",
								"size": 12,
								"text": "Burndown Chart Sxx - Sxx"
							}
						],
						"dataProvider": [

						<?
						// ********** DATE GENERATE VIA PHP **********
						for ($i = 1; $i <= $index ; $i++) { 
							echo '{"category": "'.$graphdata['date'][($index+1)-$i].'",';

							//Non working days increment (Saturday OR Sunday)
							if (date('w', strtotime($graphdata['date'][($index+1)-$i])) == 6 OR date('w', strtotime($graphdata['date'][($index+1)-$i])) == 0){
								$holidaysinc=$holidaysinc+1;
							};

							if ((($index+1)-$i)!=$index) {
								//All days of sprint expect the 1st one
								$pointsday = $graphdata['pointdone'][($index+1)-$i];
								$pointpreviousday = $graphdata['pointdone'][(($index+1)-$i)+1];
								$diffpointdone = $pointsday-$pointpreviousday;

								if ($diffpointdone <= 0) {
									echo '"Done": null,';
								}else{
									echo '"Done": "'.$diffpointdone.'",';	
								};
								
							}else{
								//First day of the sprint only
								echo '"Done": "'.$graphdata['pointdone'][($index+1)-$i].'",';
							}

							echo '"CompRem": "'.$graphdata['totpointrem'][($index+1)-$i].'",';
							echo '"Projection": "'.calcomplexity($livetotsprintcomp, $workingdaycounter, $i, $holidaysinc).'"},';
						}
						// ******************************************
						?>
						]
					}
				);
			</script>
			<!-- TO BE REMOVED WHEN CSS DONE -->
			<br><br><br><br><br>
			<center>Total sprint complexity : <h2><?echo $livetotsprintcomp;?></h2></center>
			<!-- ## -->
			<center><div id="chartdiv" style="width: 85%; height: 400px; background-color: #FFFFFF;" ></div></center>

	<?
	//print_r($graphdata);

}//END ELSE FORM DISPLAY
?>