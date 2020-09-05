<?php
session_start();

if(!isset($_SESSION["r"])) {
    $_SESSION["r"] = true;
    $_SESSION["credit"] = 1000;
}
?>
<html>
<head>
    <title>Blackjack</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <h3>Welcome to Online Casino!</h4>
    <h4>Earn money, and buy the flag</h5>
    <br />
    <strong>Current  : <span id="credit"><?=$_SESSION["credit"]?></span></strong>
    <br />
    <a href="./buy.php">Buy flag with 10,000,000 credits!</a>
    <br />
    <h4>Blackjack</h4>
    <p>Dealer : hit until 17</p> 
    <button type="button" id="start">Start a game</button>
    <div id="ingame">
       <button type="button" id="hit">Hit</button>
       <button type="button" id="stay">Stay</button>
    </div>
    <br />
    <div id="dealer"></div>
    <br />
    <div id="player"></div>
    <script>
        let credit = <?=$_SESSION["credit"]?>;
    </script>
    <script src="game.js"></script>
</body>
</html>
