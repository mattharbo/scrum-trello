<?
header("Refresh:120");//check history every 1 minutes
include './includes/functions.php';

echo htmlheader("Trellowup • Dashboard", "./includes/main.css");

// $dir = 'cache';
// $now = date(Ymd_His);

$cardevents = "https://api.trello.com/1/boards/561228dc16f33267799133c3/actions?filter=updateCard,moveCardToBoard,createCard,deleteCard&limit=500&key=65c83fd020db39e2027c509a67587125&token=d8cfaa1f1f58a0e0b7d34befc2952cc26352a3e97c948bd9f80243377f98654e";

if (!empty(fetchdataonapi($cardevents))) {
	#Folder creation
	// mkdir("./cache/".$now, 0700);
	#File creation into folder
	// file_put_contents("./cache/".$now.'/'.$now.'_board.json', fetchdataonapi($cardevents));

	$obj = json_decode(fetchdataonapi($cardevents));
	echo "<center><h2>Dashboard : ".$obj[0]->data->board->name."</h2> Last update at ".date(H.'\h'.i.'\m'.s.'\s')." local server time • ";
	appversion();
?>
<table border="0">
<?
	foreach ($obj as $event) {

		if ($event->type == "updateCard"){
			if(isset($event->data->listAfter)){
				$updateCardCounter = $updateCardCounter + 1;
				echo "<tr bgcolor='#DDF0ED'><td><img width='17px' height='17px' src='./includes/img/moved.png'></td>";
				echo "<td align='center' width='120px'>".datetreatment($event->date)."</td>";
				echo "<td align='right' width='120px'><b>".$event->memberCreator->fullName."</b></td>";
				echo "<td><a href=https://trello.com/c/".$event->data->card->shortLink." target=_blank>".$event->data->card->name ."</a></td>";
				echo "<td><b>From</b> : ".$event->data->listBefore->name."</td>";
				echo "<td><b>To</b> : ".$event->data->listAfter->name."</td>";
				echo "<td><a href='cardlife/?card=".$event->data->card->shortLink."' target=_blank><img src='./includes/img/clock.png'></a></td></tr>";
			}
		}

		if ($event->type == "moveCardToBoard"){
			$moveCardToBoardCounter = $moveCardToBoardCounter + 1;
			echo "<tr bgcolor='#FBE1B7'><td><img width='17px' height='17px' src='./includes/img/imported.png'></td>";
			echo "<td align='center' width='120px'>".datetreatment($event->date)."</td>";
			echo "<td align='right' width='120px'><b>".$event->memberCreator->fullName."</b></td>";
			echo "<td><a href=https://trello.com/c/".$event->data->card->shortLink." target=_blank>".$event->data->card->name ."</a></td>";
			echo "<td><b>Coming from</b> : ".$event->data->boardSource->name."</td>";
			echo "<td><b>Placed in</b> : ".$event->data->list->name."</td>";
			echo "<td><a href='cardlife/?card=".$event->data->card->shortLink."' target=_blank><img src='./includes/img/clock.png'></a></td></tr>";
		}
		
		if ($event->type == "createCard"){
			if(isset($event->data->board->shortLink)){
				$createCardCounter = $createCardCounter + 1;
				echo "<tr bgcolor='#FF9966'><td><b>Created</b></td>";
				echo "<td align='center' width='120px'>".datetreatment($event->date)."</td>";
				echo "<td align='right' width='120px'><b>".$event->memberCreator->fullName."</b></td>";
				echo "<td><a href=https://trello.com/c/".$event->data->card->shortLink." target=_blank>".$event->data->card->name ."</a></td>";
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
	}
?>
</table>
</center>
<?
	echo "<br>Number of card created : ".$createCardCounter."<br>";
	echo "Number of card moved : ".$updateCardCounter."<br>";
	echo "Number of card added to board from another board : ".$moveCardToBoardCounter."<br>";
	echo "Number of card deleted : ".$deletedCardCounter." (all the history is also deleted)<br>";

}else{
	echo "<br />Not connected to Internet";
}
?>

</body>
</html>