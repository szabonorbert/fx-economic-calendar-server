<?php

    require "../vendor/autoload.php";
    require "functions.php";

    //load minimum importance
    if (!isset($_ENV["min_importance"])) $_ENV["min_importance"] = 1;
    
    //get request & load prefix
    $req = $_SERVER["REQUEST_URI"];
    if (isset($_ENV["url_prefix"]) && $_ENV["url_prefix"] != "") $req = str_replace($_ENV["url_prefix"], "", $req);
    $req = trim($req, "/");
    $req = explode("/", $req);

    $year = null;
    $month = null;
    $day = null;

    $RESULT = array();
    //todo: load and return by url