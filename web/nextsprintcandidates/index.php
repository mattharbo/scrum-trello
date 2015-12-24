<?
// header("Refresh:120");//check history every 2 minutes
include '../includes/functions.php';

echo htmlheader("Fello • Next Sprint", "../includes/main.css");
echo topbar(".");

$boardlist = "https://api.trello.com/1/boards/56128e84b038bd3747a4687d/lists?cards=open&card_fields=name&fields=name&key=65c83fd020db39e2027c509a67587125&token=d8cfaa1f1f58a0e0b7d34befc2952cc26352a3e97c948bd9f80243377f98654e";

if (!empty(fetchdataonapi($boardlist))) {
	$obj = json_decode(fetchdataonapi($boardlist));
	echo "<div id='subtopbar'><center><h2>Cards candidates for next sprint</h2> Last update at ".date(H.'\h'.i.'\m'.s.'\s')."</center></div>";
?>
<div id="pagecontent">
	<div id="table">
		
<form method='POST' action='index.php'>
	<input type='submit' name='printfunction' value='Save a JSON copy'>
</form>

<?
if (!empty($_POST['printfunction'])) {
	#Folder creation
	$now = date(Ymd_His);
	mkdir("./history/".$now, 0700);
	#File creation into folder
	file_put_contents("./history/".$now.'/'.$now.'_nextsprintcandidates.json', fetchdataonapi($boardlist));
	echo "A copy of the file has been created";
}
?>

<table id="tablecontainer"><tr>
<?
	foreach ($obj as $list) {

		if (strpos($list->name,'BACK') !== false) {

			echo "<td><table>";

			foreach ($list->cards as $singlecard) {
				$numberofback++;
				$complexityaddback = $complexityaddback + fetchcomplexity($singlecard->name);
				echo "<tr><td width='25px' align='right'>".$numberofback." • </td>";
				echo "<td><font color=#4d4d4d>".$singlecard->name."</font></td></tr>";
			}

			echo "<tr><td colspan='2' align='center'><h3> Complexity : ".$complexityaddback."</h3></td></tr></table></td>";

		}

		if (strpos($list->name,'FRONT') !== false) {

			echo "<td><table>";

			foreach ($list->cards as $singlecard) {
				$numberoffront++;
				$complexityaddfront = $complexityaddfront + fetchcomplexity($singlecard->name);
				echo "<tr><td width='25px' align='right'>".$numberoffront." • </td>";
				echo "<td><font color=#539e44>".$singlecard->name."</font></td></tr>";
			}

			echo "<tr><td colspan='2' align='center'><h3> Complexity : ".$complexityaddfront."</h3></td></tr></table></td>";

		}

		if (strpos($list->name,'Conditional') !== false) {

			echo "<td><table>";

			foreach ($list->cards as $singlecard) {
				$numberofcond++;
				$complexityaddcond = $complexityaddcond + fetchcomplexity($singlecard->name);
				echo "<tr><td width='25px' align='right'>".$numberofcond." • </td>";
				echo "<td><font color=#af9b00>".$singlecard->name."</font></td></tr>";
			}

			echo "<tr><td colspan='2' align='center'><h3> Complexity : ".$complexityaddcond."</h3></td></tr></table></td>";

		}

		if (strpos($list->name,'Setupless') !== false) {

			echo "<td><table>";

			foreach ($list->cards as $singlecard) {
				$numberofsetup++;
				$complexityaddsetup = $complexityaddsetup + fetchcomplexity($singlecard->name);
				echo "<tr><td width='25px' align='right'>".$numberofsetup." • </td>";
				echo "<td><font color=#0079bf>".$singlecard->name."</font></td></tr>";
			}

			echo "<tr><td colspan='2' align='center'><h3> Complexity : ".$complexityaddsetup."</h3></td></tr></table></td>";

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
<?
}else{
	echo "<br />Not connected to Internet";
}

?>
</div><!-- table -->
</div><!-- pagecontent -->
</body>
</html>