<?

include '../includes/functions.php';
echo htmlheader("Follo • Team report", "../includes/main.css");

$totdaysofcurrentmonth = cal_days_in_month(CAL_GREGORIAN, date(m), date(Y));
$monthtextual = date(F);
$textualdate="1 ".$monthtextual." ".date(Y);
$timespanofgivenmonth=strtotime($textualdate);
$firstdayofmonth = date(w, $timespanofgivenmonth);

// 1 Monday
// 2 Tuesday
// 3 Wednesday
// 4 Thursday
// 5 Friday
// 6 Saturday
// 0 Sunday

$number=31;

if (is_int($number/7)){
	$numberofweek=$number/7;
}else{
	$numberofweek=((int)($number/7))+1;
}

echo $numberofweek;

// echo (int)(30/7);

// for ($sem=0; $sem < ((int)($totdaysofcurrentmonth/7))+1; $sem++) { 
// 	for ($jou=0; $jou < 7; $jou++) {
// 		$e=$e+1;
// 		$montharray[$sem][$jou]=$e;
// 	}
// }

// print_r($montharray);

?>
