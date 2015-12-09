<?
include '../includes/functions.php';

echo htmlheader("Trellowup • Card detail", "../includes/main.css");

$cardid = $_GET['card'];
$cardlife = "https://api.trello.com/1/cards/".$cardid."/actions?filter=moveCardToBoard,updateCard:idList&limit=100&key=65c83fd020db39e2027c509a67587125&token=d8cfaa1f1f58a0e0b7d34befc2952cc26352a3e97c948bd9f80243377f98654e";

if (!empty(fetchdataonapi($cardlife))) {
	$obj = json_decode(fetchdataonapi($cardlife));

	echo "<center><h2>".$obj[0]->data->card->name."</h2>Last update at ".date(H.'\h'.i.'\m'.s.'\s')." • ";
	appversion();
?>
<table>

	<tr align='center'>
		<td width='120px'></td>
		<td width='150px'><b>TO DO</b></td>
		<td width='150px'><b>IN PROGRESS</b></td>
		<td width='150px'><b>TO REVIEW</b></td>
		<td width='150px'><b>TO TEST</b></td>
		<td width='150px'><b>DONE</b></td>
	</tr>

<?
		foreach ($obj as $eventcard) {

			if ($eventcard->type =="moveCardToBoard"){

				if($eventcard->data->list->name =="To do back" or $eventcard->data->list->name =="To do front"){
					//alors envoi dans la 1ere col
					echo "<tr bgcolor='#F6F6F6'><td align='right'>Imported by <b>".$eventcard->memberCreator->fullName."</b></td><td bgcolor='#FFFF99' align='center'>".datetreatment($eventcard->date)."</td><td></td><td></td><td></td><td></td></tr>";
				}//test si imported en TO DO : data->list->name

				if($eventcard->data->list->name =="In Progress"){
					//alors envoi dans la 2eme col
					echo "<tr bgcolor='#F6F6F6'><td align='right'>Imported by <b>".$eventcard->memberCreator->fullName."</b></td><td></td><td bgcolor='#FFFF99' align='center'>".datetreatment($eventcard->date)."</td><td></td><td></td><td></td></tr>";
				}//test si imported en IN PROGRESS : data->list->name				

				if($eventcard->data->list->name =="To Review"){
					//alors envoi dans la 3eme col
					echo "<tr bgcolor='#F6F6F6'><td align='right'>Imported by <b>".$eventcard->memberCreator->fullName."</b></td><td></td><td></td><td bgcolor='#FFFF99' align='center'>".datetreatment($eventcard->date)."</td><td></td><td></td></tr>";
				}//test si imported en TO REVIEW : data->list->name

				if($eventcard->data->list->name =="To Test"){
					//alors envoi dans la 4eme col
					echo "<tr bgcolor='#F6F6F6'><td align='right'>Imported by <b>".$eventcard->memberCreator->fullName."</b></td><td></td><td></td><td></td><td bgcolor='#FFFF99' align='center'>".datetreatment($eventcard->date)."</td><td></td></tr>";
				}//test si imported en TO TEST : data->list->name

				if($eventcard->data->list->name =="Done"){
					//alors envoi dans la 5eme col
					echo "<tr bgcolor='#F6F6F6'><td align='right'>Imported by <b>".$eventcard->memberCreator->fullName."</b></td><td></td><td></td><td></td><td></td><td bgcolor='#FFFF99' align='center'>".datetreatment($eventcard->date)."</td></tr>";
				}//test si imported en DONE : data->list->name
			}else
			{
				if ($eventcard->data->listAfter->name =="createCard"){
					echo "<tr bgcolor='#F6F6F6'><td align='right'><b>".$eventcard->memberCreator->fullName."</b></td><td bgcolor='#FFFF99' align='center'>".datetreatment($eventcard->date)."</td><td></td><td></td><td></td><td></td></tr>";
				}
				if ($eventcard->data->listAfter->name =="In Progress"){
					echo "<tr bgcolor='#F6F6F6'><td align='right'><b>".$eventcard->memberCreator->fullName."</b></td><td></td><td bgcolor='#FFFF99' align='center'>".datetreatment($eventcard->date)."</td><td></td><td></td><td></td></tr>";
				}
				if ($eventcard->data->listAfter->name =="To Review"){
					echo "<tr bgcolor='#F6F6F6'><td align='right'><b>".$eventcard->memberCreator->fullName."</b></td><td></td><td></td><td bgcolor='#FFFF99' align='center'>".datetreatment($eventcard->date)."</td><td></td><td></td></tr>";
				}
				if ($eventcard->data->listAfter->name =="To Test"){
					echo "<tr bgcolor='#F6F6F6'><td align='right'><b>".$eventcard->memberCreator->fullName."</b></td><td></td><td></td><td></td><td bgcolor='#FFFF99' align='center'>".datetreatment($eventcard->date)."</td><td></td></tr>";
				}
				if ($eventcard->data->listAfter->name =="Done"){
					echo "<tr bgcolor='#F6F6F6'><td align='right'><b>".$eventcard->memberCreator->fullName."</b></td><td></td><td></td><td></td><td></td><td bgcolor='#FFFF99' align='center'>".datetreatment($eventcard->date)."</td></tr>";
				}
			}
		}
	}
?>
</center>
</body>
</html>