<?
// header("Refresh:120");//check history every 1 minutes
include '../includes/functions.php';

echo htmlheader("Trellowup • Next Sprint", "../includes/main.css");

echo "<form method='POST' action='index.php'>
<input type='submit' name='printfunction' value='Save a JSON copy'>
</form>";

$boardlist = "https://api.trello.com/1/boards/56128e84b038bd3747a4687d/lists?cards=open&card_fields=name&fields=name&key=65c83fd020db39e2027c509a67587125&token=d8cfaa1f1f58a0e0b7d34befc2952cc26352a3e97c948bd9f80243377f98654e";

if (!empty(fetchdataonapi($boardlist))) {
	$obj = json_decode(fetchdataonapi($boardlist));
	echo "<center><h2>Dashboard : Cards selection for next sprint</h2> Last update at ".date(H.'\h'.i.'\m'.s.'\s')." local server time • ";
	appversion();
?>
<table><tr>
<?
	foreach ($obj as $list) {

		if (strpos($list->name,'BACK') !== false) {

			echo "<td><table>";

			foreach ($list->cards as $singlecard) {
				$numberofback++;
				echo "<tr><td><font color='red'>".$singlecard->name."</font></td></tr>";
			}

			echo "</table></td>";

		}

		if (strpos($list->name,'FRONT') !== false) {

			echo "<td><table>";

			foreach ($list->cards as $singlecard) {
				$numberoffront++;
				echo "<tr><td><font color='blue'>".$singlecard->name."</font></td></tr>";
			}

			echo "</table></td>";

		}

		// if ($list->type == "updateCard"){
		// 	if(isset($list->data->listAfter)){
		// 		$updateCardCounter = $updateCardCounter + 1;
		// 		echo "<tr bgcolor='#DDF0ED'><td><img width='17px' height='17px' src='./includes/img/moved.png'></td>";
		// 		echo "<td align='center' width='120px'>".datetreatment($list->date)."</td>";
		// 		echo "<td align='right' width='120px'><b>".$list->memberCreator->fullName."</b></td>";
		// 		echo "<td><a href=https://trello.com/c/".$list->data->card->shortLink." target=_blank>".$list->data->card->name ."</a></td>";
		// 		echo "<td><b>From</b> : ".$list->data->listBefore->name."</td>";
		// 		echo "<td><b>To</b> : ".$list->data->listAfter->name."</td>";
		// 		echo "<td><a href='cardlife/?card=".$list->data->card->shortLink."' target=_blank><img src='./includes/img/clock.png'></a></td></tr>";
		// 	}
		// }

		// if ($list->type == "moveCardToBoard"){
		// 	$moveCardToBoardCounter = $moveCardToBoardCounter + 1;
		// 	echo "<tr bgcolor='#FBE1B7'><td><img width='17px' height='17px' src='./includes/img/imported.png'></td>";
		// 	echo "<td align='center' width='120px'>".datetreatment($list->date)."</td>";
		// 	echo "<td align='right' width='120px'><b>".$list->memberCreator->fullName."</b></td>";
		// 	echo "<td><a href=https://trello.com/c/".$list->data->card->shortLink." target=_blank>".$list->data->card->name ."</a></td>";
		// 	echo "<td><b>Coming from</b> : ".$list->data->boardSource->name."</td>";
		// 	echo "<td><b>Placed in</b> : ".$list->data->list->name."</td>";
		// 	echo "<td><a href='cardlife/?card=".$list->data->card->shortLink."' target=_blank><img src='./includes/img/clock.png'></a></td></tr>";
		// }
	}
?>
</tr></table>
</center>
<?
}else{
	echo "<br />Not connected to Internet";
}

echo "Number of back tickets :".$numberofback."<br>";
echo "Number of front tickets :".$numberoffront;

if (!empty($_POST['printfunction'])) {
	#Folder creation
	$now = date(Ymd_His);
	mkdir("./history/".$now, 0700);
	#File creation into folder
	file_put_contents("./history/".$now.'/'.$now.'_nextsprintcandidate.json', fetchdataonapi($boardlist));
	echo "File created";
}
?>

</body>
</html>