<?
header("Refresh:300");//check history every 5 minutes
include '../includes/functions.php';
echo htmlheader("Follo • Live stream Members Activity", "../includes/main.css");

// Get all members of the board
// https://api.trello.com/1/boards/561228dc16f33267799133c3/members?fields=avatarHash,fullName,username&key=65c83fd020db39e2027c509a67587125&token=d8cfaa1f1f58a0e0b7d34befc2952cc26352a3e97c948bd9f80243377f98654e

// Get all cards related to a member
// https://api.trello.com/1/boards/561228dc16f33267799133c3/members/---Member id---/cards?fields=name,dateLastActivity,shortUrl&members=true&member_fields=avatarHash,fullName,username,initials&list=true&list_fields=name&=false&attachments=true&attachment_fields=url&key=65c83fd020db39e2027c509a67587125&token=d8cfaa1f1f58a0e0b7d34befc2952cc26352a3e97c948bd9f80243377f98654e

// fet avatar pic :
// https://trello-avatars.s3.amazonaws.com/---avatarHash---/170.png

// $allboardmembers = "https://api.trello.com/1/boards/561228dc16f33267799133c3/members?fields=avatarHash,fullName,username&key=65c83fd020db39e2027c509a67587125&token=d8cfaa1f1f58a0e0b7d34befc2952cc26352a3e97c948bd9f80243377f98654e";

//BE CAREFUL HARDCODED MEMBERS HERE !
$allboardmembers = array
  (
  //array(id, avatarHash, fullName, username)
  array("561242cbf754cab8cae6d06a","a1441d5241c8130aa7e9faf3c0b48ae3","Arnaud Lebreton","arnaudlebreton4","Arnaud"),
  // array("4fa23fb211b91e3e65b5e843","ef30f40b727f9c30d40f164d1fc235f3","Joan-Manuel Ortega-Ardila","joanmanuelortegaardila","Joan"),
  // array("53b5546da205ddb665966514","dd5922dba92cfecd7f7a854a230ca382","Mickaël Anoufa","mickaelanoufa","Mickael"),
  array("55fa6e5e938f433370d23bee","0c87cc12e39c5380e132254b256996ee","Gaelle","gaelle94","Gaelle"),
  array("52af2930541e94e652004b97","85b7e13aa45f7e6d37fd6de7b5c45b6d","Chris Dugne","chrisdugne","Chris"),
  array("563785a574b325ce5a2be94e","a74f7b712ccfbca67e32fbfac1d6e7b7","Eric Sampaio","ericsampaio4","Eric"),
  array("55faabb71fe9809f1bce6d07","4cef9517087083d70cc068cbdae2e526","Jeroen Engels","jeroenengels","Jeroen"),
  array("5609074552d59cd5ceb8c4c7","1b272b008df77f21d0ae2ac91b8e9e64","Thibaut Lambert","t1b0","Thibaut"),
  array("55fa7aac7d53f052afdbe763","786a7ad823c02255c6773a0b54376f9c","Arthur Weber","goduuu","Arthur"),
  array("55ed4db9b0a1d88b74782bed","667cede4e2f5bc4a50c477622d95368f","Loïc Calvy","loiccalvy1","Loic"),
  array("561276d36f767ef609f437b8","28be9bea97879575a5a542491b1036fe","Matthieu Harbonnier","matthieuharbonnier","Matthieu")
  );

echo "<div id='namelisting'><ul class='namelistingul'>";
foreach ($allboardmembers as $userlisting) {
	echo "<li class='namelistingli'><a href='#".$userlisting[0]."'>".$userlisting[4]."</a></li>";
}
echo "</ul></div>";

echo "<div id='memberactivitypagecontent'>";

foreach ($allboardmembers as $user) {

	// Get a member id
	echo "<div id='memberactivity'>";
	echo "<div id='fullusername'><a name=".$user[0].">".$user[2]."</div>";
	echo "<img class='avatar' src='https://trello-avatars.s3.amazonaws.com/".$user[1]."/170.png'>";
	echo "<div id='username'>@".$user[3]."</div>";
	// Get all cards related to member id
	$urlapicardstoamember = cardsmemberurl($user[0]);
	$cardsassociatedtomember = json_decode(fetchdataonapi($urlapicardstoamember));

	foreach ($cardsassociatedtomember as $cardsmember) {

		if ($cardsmember->list->name == "In Progress") {
			echo "<div id='cardbox'>";		
				echo "<div id='lastactivity'>Since ".datetreatmentlight($cardsmember->dateLastActivity)."</div>";
				echo "<div id='cardstatus' style='background-color:#A9F5BC'>".$cardsmember->list->name."</div>";
				echo "<div id='cardtitle'>".$cardsmember->name."</div>";
				echo "<div id='githublink'><a href='".$cardsmember->attachments[0]->url."' target=_blank>GitHub issue</a></div>";
				foreach ($cardsmember->attachments as $allcardlinks) {
					$nbrofattachments = $nbrofattachments+1;
				}
				echo "<div id='githublastpr'><a href='".$cardsmember->attachments[$nbrofattachments-1]->url."' target=_blank>Latest PR </a></div>";
				$nbrofattachments = 0;

				echo "<div id='othermemberoncard'>Also with : ";
				foreach ($cardsmember->members as $allmembersoncard) {
					if ($allmembersoncard->id != $user[0]) {
						echo $allmembersoncard->initials." | ";
					}	
				}
				echo "</div>";//end div othermemberoncard
			echo "</div>";//div cardbox
		}

		// if ($cardsmember->list->name == "To Review" /*and $cardsmember->members*/) {
		// 	echo "<div id='cardbox'>";		
		// 		echo "<div id='cardtitle'>".$cardsmember->name."</div>";
		// 		echo "<div id='cardstatus' style='background-color:#F7BE81'>".$cardsmember->list->name."</div>";
		// 		echo "<div id='lastactivity'>Since ".datetreatmentlight($cardsmember->dateLastActivity)."</div>";
		// 		echo "<div id='githublink'><a href='".$cardsmember->attachments[0]->url."' target=_blank>GitHub issue</a></div>";
		// 		foreach ($cardsmember->attachments as $allcardlinks) {
		// 			$nbrofattachments = $nbrofattachments+1;
		// 		}
		// 		echo "<div id='githublastpr'><a href='".$cardsmember->attachments[$nbrofattachments-1]->url."' target=_blank>Latest PR </a></div>";
		// 		$nbrofattachments = 0;
		// 		echo "<div id='othermemberoncard'>Also with : ";
		// 		foreach ($cardsmember->members as $allmembersoncard) {
		// 			if ($allmembersoncard->id != $user[0]) {
		// 				echo $allmembersoncard->initials." / ";
		// 			}	
		// 		}
		// 		echo "</div>";
		// 	echo "</div>";//div cardbox
		// }
	}
	echo "</div>";//div memberactivity
}// end for each get dev user

?>
</div><!-- div memberactivitypagecontent -->
</body>
</html>