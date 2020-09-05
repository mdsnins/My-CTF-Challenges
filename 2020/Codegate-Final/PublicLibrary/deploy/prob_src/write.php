<?php
require 'tool.php';

$n = isset($_GET["n"]) ? $_GET["n"] : "";
$b = isset($_GET["b"]) ? $_GET["b"] : "";
if($n == "" || $b == "") {
    header("HTTP/1.1 404");
    die();
}

$n = md5(time().$n);

file_put_contents("/tmp/$n.txt", $b);

header("Content-Type: application/json");
echo json_encode(array("realname" => "$n.txt"));