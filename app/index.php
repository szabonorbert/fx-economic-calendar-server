<?php

    require "../vendor/autoload.php";
    require "functions.php";
    
    if (!isset($_ENV["min_importance"]) || $_ENV["min_importance"] == "") $_ENV["min_importance"] = 3;
    $_ENV["min_importance"] = (int)$_ENV["min_importance"];

    //get the request
    $req = $_SERVER["REQUEST_URI"];
    if (isset($_ENV["url_prefix"]) && $_ENV["url_prefix"] != "") $req = str_replace($_ENV["url_prefix"], "", $req);
    $req = trim($req, "/");
    $req = explode("/", $req);
    $req = array_filter($req);

    //load nums
    $year = 0;
    $month = 0;
    $day = 0;
    if (isset($req[0])) $year = (int)$req[0];
    if (isset($req[1])) $month = (int)$req[1];
    if (isset($req[2])) $day = (int)$req[2];
    
    //year is required
    if ($year == 0) die();

    //load data
    $result = "";
    if ($year != 0 && $month != 0 && $day != 0){
        $result = getDay($year, $month, $day);
    } else if ($year != 0 && $month != 0){
        $result = getMonth($year, $month);
    } else if ($year != 0){
        $result = getYear($year);
    }

    $result = "[" . $result . "]";
    header('Content-Type: application/json; charset=utf-8');
    echo $result;