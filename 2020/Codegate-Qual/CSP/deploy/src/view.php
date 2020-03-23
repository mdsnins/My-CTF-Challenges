<?php
require_once 'config.php';

$n = "";
$p1 = "";
$p2 = "";

if(!isset($_GET["name"]) || !isset($_GET["p1"]) || !isset($_GET["p2"]))
    header("Location: /");

$n = $_GET["name"];
$p1 = $_GET["p1"];
$p2 = $_GET["p2"];

$q = base64_encode($n).",".base64_encode($p1).",".base64_encode($p2);

$sig = md5($salt.$q);

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Advanced Echo Service</title>
  </head>
  <body>
    <p>API Name (Required): </p>
    <input name="name" type="text" required value="<?= $n ?>" disabled /> 
    <p>API Param#1 (Optional) : </p>
    <input name="p1" type="text" value="<?= $p1 ?>" disabled />
    <p>API Param#2 (Optional) : </p>
    <input name="p2" type="text" value="<?= $p2 ?>" disabled />
    <br />
    <strong>Are you sure?</strong>
    <button>Run my own Echo!</button>
    <br />
    <div id="result_view">
        <strong>Echo result...</strong>
        <br />
        <iframe src="/api.php?sig=<?= $sig ?>&q=<?= base64_encode($q)?>"></iframe>
    </div>
  </body>
</html>
