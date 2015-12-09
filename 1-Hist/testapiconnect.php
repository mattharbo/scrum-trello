<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Tuto PHP API</title>
</head>

<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script src="https://api.trello.com/1/client.js?key=65c83fd020db39e2027c509a67587125"></script>
<link rel="stylesheet" type="text/css" href="main.css">

<body>
    <div id="loggedout">
        <a id="connectLink" href="#">Connect To Trello</a>
    </div>

    <div id="loggedin">
        <div id="header">
            Logged in to as <span id="fullName"></span> 
            <a id="disconnect" href="#">Log Out</a>
        </div>
        
        <div id="output"></div>
    </div>    

<script src="mhatrello.js"></script>

</body>
</html>