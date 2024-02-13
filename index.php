<?php 
session_start();

$env_file = parse_ini_file('.env');
if (isset($env_file['PASSWORD']) && isset($_POST['password'])) {
    if (($env_file['PASSWORD']) == md5($_POST['password'])) $_SESSION["sessionid"] = time();
}

if (isset($_GET['logout'])) { 
    session_destroy();
    header("Location: ".strtok($_SERVER['REQUEST_URI'], '?'));
    die();
}

if (!isset($_SESSION["sessionid"]) && isset($env_file['PASSWORD'])) include_once("pages/login.php");
else include_once("pages/radio.php");