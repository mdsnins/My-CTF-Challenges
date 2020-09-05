<?php
session_start();

if($_SESSION["credit"] >= 10000000)
    die(getenv("FLAG"));
else
    die("Poor :P");
