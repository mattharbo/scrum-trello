<?
//Put a _POST variable here
$bool = "false";

if ($bool == "true") {
	header("Content-Disposition: attachment; filename=\"" . basename($File) . "\"");
	header("Content-Type: application/force-download");
	header("Content-Length: " . filesize($File));
	header("Connection: close");
}

header("Refresh:300");//check history every 5 minutes
include '../includes/functions.php';
echo htmlheader("Follo • Sprint sum up", "../includes/main.css");
echo topbar("..");

$boardcards = "https://api.trello.com/1/boards/561228dc16f33267799133c3/cards?fields=name,idList,url&key=65c83fd020db39e2027c509a67587125&token=d8cfaa1f1f58a0e0b7d34befc2952cc26352a3e97c948bd9f80243377f98654e";

//List IDs
$todoback="5624c1960f77228480aa4049";
$todofront="5624c1a4bdf3a7868bc704f5";
$inprogress="561244c5945acda7e5626f90";
$toreview="561244d2b74a46b20115ea3c";
$totest="561244d5d3830880a92e8c1e";
$donelist="561b9681095780ac16807534";

echo "<br><br><br><br><br>";

if (!empty(fetchdataonapi($boardcards))) {

	$obj = json_decode(fetchdataonapi($boardcards));

	foreach ($obj as $card) {

		if ($card->idList == $donelist){
			echo "<input type='checkbox' name='choix1' value='".$card->name."'> ".$card->name."<br>";
		}

	}

}else{
	echo "Something wrong with the network.";
}



?>
</div><!-- div pagecontent -->
</body>
</html>