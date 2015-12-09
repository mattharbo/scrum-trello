<?php

//BE CAREFUL HARDCODED MEMBERS HERE !
$allboardmembers = array
  (
  //array(id, avatarHash, fullName, username)
  array("561242cbf754cab8cae6d06a","a1441d5241c8130aa7e9faf3c0b48ae3","Arnaud Lebreton","arnaudlebreton4","Arnaud"),
  array("4fa23fb211b91e3e65b5e843","ef30f40b727f9c30d40f164d1fc235f3","Joan-Manuel Ortega-Ardila","joanmanuelortegaardila","Joan"),
  array("53b5546da205ddb665966514","dd5922dba92cfecd7f7a854a230ca382","Mickaël Anoufa","mickaelanoufa","Mickael"),
  array("55fa6e5e938f433370d23bee","0c87cc12e39c5380e132254b256996ee","Gaelle","gaelle94","Gaelle"),
  array("52af2930541e94e652004b97","85b7e13aa45f7e6d37fd6de7b5c45b6d","Chris Dugne","chrisdugne","Chris"),
  array("563785a574b325ce5a2be94e","a74f7b712ccfbca67e32fbfac1d6e7b7","Eric Sampaio","ericsampaio4","Eric"),
  array("55faabb71fe9809f1bce6d07","4cef9517087083d70cc068cbdae2e526","Jeroen Engels","jeroenengels","Jeroen"),
  array("5609074552d59cd5ceb8c4c7","1b272b008df77f21d0ae2ac91b8e9e64","Thibaut Lambert","t1b0","Thibaut"),
  array("55fa7aac7d53f052afdbe763","786a7ad823c02255c6773a0b54376f9c","Arthur Weber","goduuu","Arthur"),
  array("55ed4db9b0a1d88b74782bed","667cede4e2f5bc4a50c477622d95368f","Loïc Calvy","loiccalvy1","Loic")
  );

// Loading existing data:
$json = file_get_contents("./test.json");
$data = json_decode($json, true);


//Get how many line are in the JSON file
foreach ($data as $insert) {
	$numberofline = $numberofline + 1;
}

echo $numberofline;

// Adding new data into the JSON file
$data[$numberofline+1] = [
'id' => '2',
'name' => 'Two',
'dateLastActivity' => '',
'shortUrl' => '',
'attachments' => [
	['id'=>'id1','url'=>'url1'],
	['id'=>'id2','url'=>'url2']
	],
'list'=>['id'=>'','name'=>''],
'members'=>[['id'=>'','avatarHash'=>'','fullName'=>'','username'=>'','initials'=>'']]
];

// Writing modified data
file_put_contents('./test.json', json_encode($data));

?>