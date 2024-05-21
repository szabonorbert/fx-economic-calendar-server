<?php

    require "../vendor/autoload.php";
    require "settings.php";
    require "functions.php";

    //build the request
    $req = $_SERVER["REQUEST_URI"];
    if ($_setting["url_prefix"] != "") $req = str_replace($_setting["url_prefix"], "", $req);
    $req = trim($req, "/");
    $req = str_replace(".json", "", $req);
    $req = explode("/", $req);
    $req = array_filter($req);
    $req = array_values($req);

    //load nums
    $year = 0;
    $month = 0;
    $day = 0;
    if (isset($req[0])) $year = (int)$req[0];
    if (isset($req[1])) $month = (int)$req[1];
    if (isset($req[2])) $day = (int)$req[2];

    //echo $year . "-" . $month . "-" . $day;

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

    //show results
    switch ($_setting["export"]){
        
        case "array":
            header('Content-Type: text/html; charset=utf-8');
            echo "<pre>";
            print_r($result);
            break;

        case "lines":
            header('Content-Type: text/plain; charset=utf-8');
            foreach ($result as $key => $value){
                echo $key . ";" . implode(";", $value) . PHP_EOL;
            }
            break;

        case "csv":
            header('Content-Type: text/csv; charset=utf-8');
            echo "id;title;currency;date;importance" . PHP_EOL;
            foreach ($result as $key => $value){
                echo $key . ";" . implode(";", $value) . PHP_EOL;
            }
            break;

        case "json":
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($result);
            break;
    }