<?php

    require "../vendor/autoload.php";
    require "functions.php";
    
    //get the request
    $req = $_SERVER["REQUEST_URI"];
    if (isset($_ENV["url_prefix"]) && $_ENV["url_prefix"] != "") $req = str_replace($_ENV["url_prefix"], "", $req);
    $req = trim($req, "/");
    $req = explode("/", $req);
    $req = array_filter($req);
    
    //load nums
    $year = null;
    $month = null;
    $day = null;
    if (isset($req[2])) $day = $req[2];
    if (isset($req[1])) $month = $req[1];
    if (isset($req[0])) $year = $req[0];

    //year is required
    if ($year === null) die();

    //load result
    $result = array();
    loadData($year, $month, $day, $result);
    
