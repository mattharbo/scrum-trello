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

//$allboardcards = "https://api.trello.com/1/boards/561228dc16f33267799133c3/cards?fields=name,idList&key=65c83fd020db39e2027c509a67587125&token=d8cfaa1f1f58a0e0b7d34befc2952cc26352a3e97c948bd9f80243377f98654e";
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

	$graphdata = array('date' => array(), 'totpointrem' => array(), 'totpointboard' =>array(), 'pointdone' =>array(), 'realpointdone' => array());

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
				else{
					//Monday check when Sunday json file has not been created
					file_existance($foldername);
					?><form method='POST' action='./filecreation.php'>
						<input type='hidden' name='variable' value=<?echo$foldername;?>>
						<input type='submit' value='Repair'>
					</form><?
				}
			}else{
				//Monday check when Saturday json file has not been created
				file_existance($foldername);
				?><form method='POST' action='./filecreation.php'>
					<input type='hidden' name='variable' value=<?echo$foldername;?>>
					<input type='submit' value='Repair'>
				</form><?
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
				
				$boardcomplexity = 0; //variable of total points in board
				$complexity4scrum = 0; //variable of total points remaining in board
				
				//Read each list details
				foreach ($obj as $lists) {

					if ($lists->name == "To do back" OR $lists->name == "To do front" OR $lists->name == "In Progress" OR $lists->name == "To Review") {
				 		//Read each card details from the list been watched
				 		$listcomplexity = 0;
				 		
				 		foreach ($lists->cards as $cardsoflist) {
				 			$listcomplexity = $listcomplexity + fetchcomplexity($cardsoflist->name);
				 		}

				 		$complexity4scrum = $complexity4scrum + $listcomplexity;
			 			$boardcomplexity = $boardcomplexity + $listcomplexity;
					}

					else{
				 		//Read each card details from the list been watched
				 		$donecomplexity=0;
				 		$listcomplexity = 0;
				 		$complexitydone = 0;//variable of total points done in board
				 		
				 		foreach ($lists->cards as $cardsoflist) {
				 			$listcomplexity = $listcomplexity + fetchcomplexity($cardsoflist->name);
				 		}
				 	
			 			$boardcomplexity = $boardcomplexity + $listcomplexity;
			 			$complexitydone = $complexitydone + $listcomplexity;

			 			if ($lists->name == "Done") {
			 				foreach ($lists->cards as $cardsoflists) {
				 				$donecomplexity=$donecomplexity+1;
				 			}

				 			//insert board done NUMBER OF CARDS into graphdata array (AND NOT POINT DONE)
	 						$graphdata['pointdone'][$index]=$donecomplexity;

	 						//insert board done POINTS into graphdata array
	 						$graphdata['realpointdone'][$index]=$complexitydone;				 			
			 			}
					}	
		 		}
		 	}

			//insert board complexity into graphdata array
		 	if ($complexity4scrum == 0) {
		 		$graphdata['totpointrem'][$index]='null';
		 		$graphdata['pointdone'][$index]='null';
		 		$graphdata['realpointdone'][$index]='null';
		 	}else{
		 		$graphdata['totpointrem'][$index]=$complexity4scrum;
		 	}

		 	//insert board complexity into graphdata array
	 		$graphdata['totpointboard'][$index]=$boardcomplexity;
		}
	}

	//print_r($graphdata);
	//echo "<br>";

	//---- UPDATE ARRAY DATA IN CASE OF VELOCITY UPDATE ----
	//Parse the entire data array
	for ($parse = $index; $parse > 0 ; $parse--) { 
		//if (totpointboard day X minus totpointboard day X-1) > 0 AND NOT FOR the 1st day of the sprint
		if (($graphdata['totpointboard'][$parse]-$graphdata['totpointboard'][$parse+1]) > 0 AND $parse != $index) {
			// >> A = totpointboard day X minus totpointboard day X-1
			$difftotpointboard = ($graphdata['totpointboard'][$parse]-$graphdata['totpointboard'][$parse+1]);
			// >> B = pointdone day X minus pointdone day X-1
			$difftotpointdone = ($graphdata['realpointdone'][$parse]-$graphdata['realpointdone'][$parse+1]);
			// >> X = A - B
			$unrealizedaddedpoint = $difftotpointboard - $difftotpointdone;
			// >> pointrem + X for all previous days (X-1 to 1)
			for ($z=$parse+1; $z <= $index ; $z++) { 
				$graphdata['totpointrem'][$z] = $graphdata['totpointrem'][$z]+$unrealizedaddedpoint;
			}
		}
	}

	$livetotsprintcomp=max($graphdata['totpointboard']);
	$holidays = $index-$workingdaycounter;

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
			<center>Total sprint complexity : <h2><?echo $livetotsprintcomp;?></h2><? echo $difftotpointboard;?> point(s) added during sprint</center>
			<!-- ## -->
			<center><div id="chartdiv" style="width: 85%; height: 400px; background-color: #FFFFFF;" ></div></center>

	<?
	//print_r($graphdata);

}//END ELSE FORM DISPLAY
?>