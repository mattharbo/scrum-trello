<html>
<head>
<title>Follo | bloblo</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.0/jquery.min.js"></script>
</head>
<body>
<!-- <img id="loader" src="./infinity.gif" /> -->
<?
	include './testfunction.php';
	$cardevents = "https://api.trello.com/1/boards/561228dc16f33267799133c3/actions?filter=updateCard,moveCardToBoard,createCard,deleteCard&limit=500&key=65c83fd020db39e2027c509a67587125&token=d8cfaa1f1f58a0e0b7d34befc2952cc26352a3e97c948bd9f80243377f98654e";
?>


<script>
$(document).ready(function(){
        submit_form();
});

function submit_form () {
    function(data) {
    	$('#results').html(data);
   	});
}
</script>

<div id="results">
	<?fetchdataonapi($cardevents);?>
</div>

</body>
</html>
<!-- <script type="text/javascript">
$('#loader').hide();
</script> -->