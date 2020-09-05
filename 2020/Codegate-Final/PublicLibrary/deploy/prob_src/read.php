<?php

function returnJSON($obj) {
    header("Content-Type: application/json");
    die(json_encode($obj));
}

function cleanpath($path) {
    $abs_prefix = ($path[0] === "/") ? "/" : "";
    $paths = explode("/", $path);
    $p = array();
    foreach($paths as $x) {
        switch($x) {
            case "":
            case ".":
                break;
            case "..":
                array_pop($p);
                break;
            default:
                $p[] = $x;
            break;
        }
    }
    return $abs_prefix.implode("/", $p);
}

$salt = "y0u_w1ll_N3v3r_gu3s5_tH1s_Sup3rS3cr3T_\x20\x18\x10\x29_h4h4";

$b = isset($_GET["book"]) ? $_GET["book"]  : "";
$a = isset($_GET["auth"]) ? $_GET["auth"]  : "";

if(!$b) {
    returnJSON(array(
        "code" => "error",
        "msg" => "no hack"
    ));
}

if(substr(md5($salt.$b), 0, 8) != $a) {
    returnJSON(array(
        "code" => "error",
        "msg" => "wrong authcode"
    ));
}

$b = str_replace(":", "/", $b);
$b = cleanpath($b);
if(!is_file($b)) {
    returnJSON(array(
        "code" => "error",
        "msg" => "no such file"
    ));
}

returnJSON(array(
    "code" => "ok",
    "body" => file_get_contents($b)
));
