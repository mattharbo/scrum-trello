


<?php

function cleanString($string) {
    //Suppression caractère spéciaux : majuscules ; / ? : @ & = + $ , . ! ~ * ( ) les espaces multiples et les underscore
    $string = strtolower($string);
    $string = preg_replace("/[^a-z0-9_'\s-]/", "", $string);
    $string = preg_replace("/[\s-]+/", " ", $string);
    $string = preg_replace("/[\s_]/", " ", $string);
    return $string;
}

//Verification post formulaire
if(isset($_POST['mot']) && !empty($_POST['mot'])) {
    
    //Encryption donnée formulaire
    $motRecherche = urlencode(cleanString($_POST['mot']));

    $dir = 'cache';
    $match = '';

    //Parse de tous les fichiers .json du dossier "Cache"
    foreach(glob($dir.'/*.json') as $fichier) {
        
        //Vérification présence d'un fichier du cache existant pour la recherche
        if(basename($fichier, '.json') == $motRecherche) {
            $match = $fichier;
        }
    }
}
else{
    print("Please fill in the form");
}

//Si fichier cache trouvé inférieur à 60s alors on le lit
if($match != '' && (time()-filemtime($match) < 60)) {
        $raw = file_get_contents($match);
        $json = json_decode($raw);
}
else {//Sinon appel de l'API Twitter
    //$url = "http://search.twitter.com/search.json?q=".$motRecherche."&rpp=10&include_entities=true&result_type=recent&lang=fr&locale=fr";
    $url = "https://trello.com/b/7Csa1r8z/current-sprint.json";

    $raw = file_get_contents($url);
    file_put_contents($dir . '/' . $motRecherche . '.json', $raw);
    //$json = json_decode($raw);
    print("API called<br>");
}

//Affichage résultat du contenu fichier JSON
if(!empty($json->results)) {
    // 	foreach($json->results as $msg) {
    //     	echo "<u>" . $msg->from_user_name ."</u> : ". $msg->text;
    //     	echo "<br />";
    //     }
    // }else {
    //     echo "Rien n'a &eacute;t&eacute; trou&eacute;.";
    // }
}else {
    echo "No research done<br>";
}

?>