<?php
header("Location: ./");	

//Here is the script that create a file into empty 
$dateretrieve = $_POST["variable"];

$firsthyphen = strpos($dateretrieve, "-")+1;
$secondhyphen = strpos($dateretrieve, "-", $firsthyphen)+1;
$yyyy=substr($dateretrieve, 0, 4);
$mm=substr($dateretrieve, $firsthyphen,2);
$dd=substr($dateretrieve, $secondhyphen,2);

//Saturday
if (date('w', strtotime($dateretrieve)) == 6) {
	//Minus 1 to catch friday's data
	$newdd = $dd-1;
	echo "Go and fetch ".$dd." json file";
	copy("./".$yyyy."-".$mm."-".$newdd."/".$yyyy."-".$mm."-".$newdd.".json", "./".$yyyy."-".$mm."-".$dd."/".$yyyy."-".$mm."-".$dd.".json");
	echo "done";
}

//Sunday
if (date('w', strtotime($dateretrieve)) == 0) {
	//Minus 2 to catch friday's data
	$newdd = $dd-2;
	echo "Go and fetch ".$dd." json file";
	copy("./".$yyyy."-".$mm."-".$newdd."/".$yyyy."-".$mm."-".$newdd.".json", "./".$yyyy."-".$mm."-".$dd."/".$yyyy."-".$mm."-".$dd.".json");
	echo "dine";
}

?>