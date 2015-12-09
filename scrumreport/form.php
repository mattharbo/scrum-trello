<?php
header("Location: ./");	 

$datediff = strtotime($_POST["enddate"]) - strtotime($_POST["begindate"]);
//+1 day 86400
$numberoffolder = floor($datediff/(60*60*24));

for ($i=0; $i < $numberoffolder+1; $i++) { 
	$foldernamecreation = date('Y-m-d', strtotime("+$i day",strtotime($_POST["begindate"])));

	echo $i." folder ".$foldernamecreation."<br>";

	#Folder creation
	mkdir("./".$foldernamecreation."/", 0700);
}
?>