<?
$listsalected=$_POST['listselected'];
$id=0;

include '../includes/functions.php';
echo htmlheader("Follo • Sprint sum up", "../includes/main.css");
echo topbar("..");

$boardcards = "https://api.trello.com/1/boards/561228dc16f33267799133c3/cards?fields=name,idList,url&members=true&key=65c83fd020db39e2027c509a67587125&token=d8cfaa1f1f58a0e0b7d34befc2952cc26352a3e97c948bd9f80243377f98654e";

//List IDs
$listid = array('To do back' => '5624c1960f77228480aa4049',
	'To do front'=>'5624c1a4bdf3a7868bc704f5',
	'In progress'=>'561244c5945acda7e5626f90',
	'To review'=>'561244d2b74a46b20115ea3c',
	'To test'=>'561244d5d3830880a92e8c1e',
	'Done'=>'561b9681095780ac16807534');

$memberid = array('55fa6e5e938f433370d23bee' => 'Gaelle',
	'52af2930541e94e652004b97' => 'Chris',
	'563785a574b325ce5a2be94e' => 'Eric',
	'55faabb71fe9809f1bce6d07' => 'Jeroen',
	'5609074552d59cd5ceb8c4c7' => 'Thibaut',
	'55fa7aac7d53f052afdbe763' => 'Arthur',
	'55ed4db9b0a1d88b74782bed' => 'Loic');

echo "<br><br><br><br><br>";

// ################ 1st form ################

if (!isset($listsalected)) {
		echo "<div id='firstform'><b>Please select a list : </b><br><br>";
		echo "<form action='./' method='POST'><select name='listid'>";

	foreach ($listid as $listchoices => $value1) {
		echo "<option value='".$value1."'>".$listchoices."</option>";
	}

	echo "</select>";
	echo "<input type='hidden' name='listselected' value='true'>";
	echo "<center><input type='submit' value='Validate' class='validatebutton'></center></form></div>";
}

if (isset($_POST['listid'])) {
	// ################ 2nd form ################

	echo "<div id='secondform'><b>Name of the list previously selected :</b> ".array_search($_POST['listid'], $listid)."<br>";
	echo "<br><form action='./generatefile.php' method='post' id ='frm2'>";

	echo "<input type='hidden' name='namelistselected' value='".array_search($_POST['listid'], $listid)."'>";

	if (!empty(fetchdataonapi($boardcards))) {

		$obj = json_decode(fetchdataonapi($boardcards));

		foreach ($obj as $card) {

			if ($card->idList == $_POST['listid']){
				$id = $id+1;
				echo "<input type='checkbox' name='card".$id."'' value='(@".$memberid[$card->members[1]->id].") • ".$card->name."'> <a href='".$card->url."' target='_blank'>".$card->name."</a><br>";
			}
		}
		echo "<br><input type='checkbox' name='checkall' onclick='checkedAll(frm2);'> <b>SELECT ALL CARDS</b>";
	}else{
		echo "Something wrong with the network.";
	}

echo "<center><input type='submit' value='Generate a .txt file' class='validatebutton'></form><br><br><a href='./'>Back to list choice</a></center></div>";
}

?>
</div><!-- div pagecontent -->
</body>
</html>