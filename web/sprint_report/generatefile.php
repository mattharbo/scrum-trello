<?
$date=date("Y-m-d_H-i-s");
$tobeprinted="Cards selected for sprint demo :\n";

foreach ($_POST as $cardchecked=>$value) {
	$tobeprinted=$tobeprinted."\n".$value;
}

header("Content-Disposition: attachment; filename=Sprint_Report_".$date.".txt");
header("Content-Type: application/force-download");
print $tobeprinted;
header("Connection: close");
?>