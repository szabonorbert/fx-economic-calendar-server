<?php

    $_setting["dailyfx_url"] = "https://www.dailyfx.com/economic-calendar/events";
    $_setting["url_prefix"] = "awdawda";
    $_setting["min_importance"] = 3;

    foreach ($_setting as $key => $value){
        if (getenv($key) !== false && trim(getenv($key)) != "") $_setting[$key] = trim(getenv($key));
    }

    $_setting["dailyfx_url"] = trim($_setting["dailyfx_url"], "/");
    $_setting["url_prefix"] = trim($_setting["url_prefix"], "/");
    $_setting["min_importance"] = (int)$_setting["min_importance"];