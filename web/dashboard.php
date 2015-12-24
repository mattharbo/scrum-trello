<?
header("Refresh:120");//check history every 2 minutes
include './includes/functions.php';

$datepickedup = pickurldate();

echo htmlheader("Follo • Dashboard", "./includes/main.css");
echo topbar(".");

$cardevents = "https://api.trello.com/1/boards/561228dc16f33267799133c3/actions?filter=updateCard,moveCardToBoard,createCard,deleteCard&limit=500&key=65c83fd020db39e2027c509a67587125&token=d8cfaa1f1f58a0e0b7d34befc2952cc26352a3e97c948bd9f80243377f98654e";

if (!empty(fetchdataonapi($cardevents))) {
	$obj = json_decode(fetchdataonapi($cardevents));
	echo "<div id='subtopbar'><center><h2>Dashboard : ".$obj[0]->data->board->name."</h2> Last update at ".date(H.'\h'.i.'\m'.s.'\s')."<br><br>• ";
	displaycalendar();
	echo "</center></div>";
?>
<div id="pagecontent">
	<img id="loader" src="./includes/img/infinity.gif"/>;
	<div id="table">
		<table border="0" id="tablecontainer">
<?
	foreach ($obj as $event) {
		$numberofapireturn = $numberofapireturn + 1;

		if (findadate($event->date, $datepickedup[0], $datepickedup[1], $datepickedup[2]) == 1) {

			if ($event->type == "updateCard"){
				if(isset($event->data->listAfter)){
					$updateCardCounter = $updateCardCounter + 1;
					echo "<tr bgcolor='#DDF0ED'><td><img width='17px' height='17px' src='./includes/img/moved.png'></td>";
					echo "<td align='center' width='120px'>".datetreatment($event->date)."</td>";
					echo "<td align='right' width='120px'><b>".$event->memberCreator->fullName."</b></td>";
					echo "<td width='500px'><a href=https://trello.com/c/".$event->data->card->shortLink." target=_blank>".$event->data->card->name ."</a></td>";
					echo "<td align='center' width='130px'><b>From</b> : ".$event->data->listBefore->name."</td>";

					if ($event->data->listAfter->name == "To do back") {
						$textcolor = "#DDF0ED";
					}

					if ($event->data->listAfter->name == "To do front") {
						$textcolor = "#DDF0ED";
					}

					if ($event->data->listAfter->name == "To Test") {
						$textcolor = "#1EAC9B";
					}

					if ($event->data->listAfter->name == "In Progress") {
						$textcolor = "#DDF0ED";
					}

					if ($event->data->listAfter->name == "To Review") {
						$textcolor = "#DDF0ED";
					}

					if ($event->data->listAfter->name == "Done") {
						$textcolor = "#DDF0ED";
					}

					echo "<td align='center' width='130px' style='background-color:".$textcolor."'><b>To</b> : ".$event->data->listAfter->name."</td>";
					echo "<td><a href='cardlife/?card=".$event->data->card->shortLink."' target=_blank><img src='./includes/img/clock.png'></a></td></tr>";
				}
			}

			if ($event->type == "moveCardToBoard"){
				$moveCardToBoardCounter = $moveCardToBoardCounter + 1;
				echo "<tr bgcolor='#FBE1B7'><td><img width='17px' height='17px' src='./includes/img/imported.png'></td>";
				echo "<td align='center' width='120px'>".datetreatment($event->date)."</td>";
				echo "<td align='right' width='120px'><b>".$event->memberCreator->fullName."</b></td>";
				echo "<td width='500px'><a href=https://trello.com/c/".$event->data->card->shortLink." target=_blank>".$event->data->card->name ."</a></td>";
				echo "<td align='center' width='120px'><b>Coming from</b> : ".$event->data->boardSource->name."</td>";
				echo "<td align='center' width='120px'><b>Placed in</b> : ".$event->data->list->name."</td>";
				echo "<td><a href='cardlife/?card=".$event->data->card->shortLink."' target=_blank><img src='./includes/img/clock.png'></a></td></tr>";
			}
			
			if ($event->type == "createCard"){
				if(isset($event->data->board->shortLink)){
					$createCardCounter = $createCardCounter + 1;
					echo "<tr bgcolor='#FF9966'><td><b>Created</b></td>";
					echo "<td align='center' width='120px'>".datetreatment($event->date)."</td>";
					echo "<td align='right' width='120px'><b>".$event->memberCreator->fullName."</b></td>";
					echo "<td width='500px'><a href=https://trello.com/c/".$event->data->card->shortLink." target=_blank>".$event->data->card->name ."</a></td>";
					echo "<td colspan='2'><b>Placed in</b> : ".$event->data->list->name."</td>";
					echo "<td><a href='cardlife/?card=".$event->data->card->shortLink."' target=_blank><img src='./includes/img/clock.png'></a></td></tr>";
				}
			}

			if ($event->type == "deleteCard"){
				$deletedCardCounter = $deletedCardCounter + 1;
				echo "<tr bgcolor='#EBC3BD'><td><img width='17px' height='17px' src='./includes/img/deleted.png'></td>";
				echo "<td align='center' width='120px'>".datetreatment($event->date)."</td>";
				echo "<td colspan='5'><b>".$event->memberCreator->fullName."</b></td></tr>";
			}
		}else{
			$numberofnothing = $numberofnothing + 1;
		}// End if • Date control
	}// End for each • Response line of the API 
?>

</table>

<?
if ($numberofapireturn == $numberofnothing) {
	echo "No team activity on Trello at this date.";
}
?>

</div><!-- div table -->

<?
	// echo "<br>Number of card created : ".$createCardCounter."<br>";
	// echo "Number of card moved : ".$updateCardCounter."<br>";
	// echo "Number of card added to board from another board : ".$moveCardToBoardCounter."<br>";
	// echo "Number of card deleted : ".$deletedCardCounter." (all the history is also deleted)<br>";

}else{
	echo "<br />Please, make sure you are connected to internet.";
}
?>

</div><!-- div pagecontent -->
<script>
jQuery(window).load(function() {
	jQuery('#loader').hide();
});
</script>
</body>
</html>