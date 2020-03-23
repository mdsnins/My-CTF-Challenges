<?php

require '/var/www/flag.php';

if($_COOKIE["FLAG"] !== $flag) {
    header("HTTP/1.1 404");
    die();
}
else {
    require_once '__db_conn_.php';
    $stmt = $conn->prepare("SELECT id, link, ip FROM que LIMIT 0, 1");
    $fetch = $stmt->execute();
    $r = $fetch->fetchArray();
    //0 -> id, 1-> link, 2->ip
    file_put_contents("/tmp/admin_log", "\n".$r[2]." -> ".$r[0], FILE_APPEND | LOCK_EX);

    $stmt = $conn->prepare("DELETE FROM que where id = :id");
    $stmt->bindValue(":id", $r[0], SQLITE3_TEXT);
    $stmt->execute();
    $conn->close();
    header("Location: ".$r[1]);
}
