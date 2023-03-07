<?php

require_once('./config/env.php');

$link = mysqli_connect($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_SCHEMA']);

if(!$link)
{
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

?>