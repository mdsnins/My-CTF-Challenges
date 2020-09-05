<?php
function startsWith($haystack, $needle) {
    $length = strlen( $needle );
    return substr( $haystack, 0, $length ) === $needle;
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

function getPermission($conn, $prefix) {
    $query = "SELECT r, w FROM permission_list WHERE prefix = '$prefix'";
}

function read($path) { 
    $path = realpath($path);
    $allow_prefixes = "SELECT prefix FROM permission_list WHERE r = 1";
    
    $readable = false;
    foreach($allow_prefixes as $p) {
        if (startsWith($path, $p))
            $readable = true;
    }

    if(!$readable)
        return "";
        
    return file_get_contents($path);
}

function write($path, $body) { 
    $path = cleanpath($path);
    $allow_prefixes = "SELECT prefix FROM permission_list WHERE w = 1";
    
    $writable = false;
    foreach($allow_prefixes as $p) {
        if (startsWith($path, $p))
            $writable = true;
    }

    if(!$writable)
        return false;

    @file_put_contents($path, $body);
    return true;
}