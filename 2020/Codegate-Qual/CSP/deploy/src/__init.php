<?php
include_once("__db_conn_.php");

if(file_exists(".init_done"))
  die("");

$conn->exec("CREATE TABLE IF NOT EXISTS que (
            id VARCHAR(64) NOT NULL UNIQUE,
            link TEXT,
            ip TEXT
          );");
