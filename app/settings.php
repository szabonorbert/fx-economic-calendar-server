<?php

    $_setting["dailyfx_url"] = "https://www.dailyfx.com/economic-calendar/events";
    $_setting["install_folder"] = "";
    $_setting["min_importance"] = 3;
    $_setting["export"] = "json";

    foreach ($_setting as $key => $value){
        if (getenv($key) !== false && trim(getenv($key)) != "") $_setting[$key] = trim(getenv($key));
    }

    $_setting["dailyfx_url"] = trim($_setting["dailyfx_url"], "/");
    $_setting["install_folder"] = trim($_setting["install_folder"], "/");
    $_setting["min_importance"] = (int)$_setting["min_importance"];

    if (isset($_GET["export"]) && in_array($_GET["export"], array("json", "csv", "lines", "array"))){
        $_setting["export"] = $_GET["export"];
    }