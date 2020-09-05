<?php

$dbserver = "localhost";
$dbconn = mysqli_connect($dbserver, "user", "v3ryh4rdp455w0rd", "pl");

function writeGuestbook($conn, $n, $a, $p) {
    $n = mysqli_real_escape_string($conn, $n);
    $a = mysqli_real_escape_string($conn, $a);
    $p = mysqli_real_escape_string($conn, $p);
    $query = 'INSERT INTO guestbook (`ip`, `name`, `age`, `purpose`) VALUES ("'.$_SERVER["REMOTE_ADDR"]
            .'", "'.$n
            .'", "'.$a
            .'", "'.$p
            .'")';

    
    if(!mysqli_query($conn, $query)) {
        return false;
    }
    return true;
}

function searchGuestbook($conn, $column, $value) {
    if(strpos($column, "\\" !== false)) 
        return -1;
    if(preg_match('/(and|or|null|limit)/i', $column)) 
        return -1;
    if(preg_match('/(union|having)/i', $column)) 
        return -1;
    
    $value = mysqli_real_escape_string($conn, $value);
    $query = "SELECT `$column` FROM guestbook WHERE `$column` LIKE \"%$value%\"";
    if(strlen($query) > 250)
        return -1;
    
    $r = array();
    $result = mysqli_query($conn, $query);
    
    if($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $t = "";
            foreach($row as $key => $value) {
                $t = substr($value, 0, 10);
            }
            $r[] = $t;
        }
        mysqli_free_result($result);
    } else {
        return -1;
    }

    return $r;
}
