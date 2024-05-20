<?php

    function loadYear($year) {
        for ($i = 1; $i <= 12; $i++){
            loadMonth($year, $i);
        }
    }
    
    function loadMonth($year, $month){
        $month_str = sprintf("%02d", $month);
        $maxdays = new DateTime($year."-".$month_str."-01");
        $maxdays = $maxdays->format('t');
        for ($i = 1; $i <= $maxdays; $i++){
            loadDay($year, $month, $i);
        }
    }

    function loadDay($year, $month, $day){
        $month = sprintf("%02d", $month);
        $day = sprintf("%02d", $day);
        $filename = "data/" . $year . "-" . $month . "-" . $day;
        echo $filename; echo "<br>";
        if (is_file($filename)){
            $file = file_get_contents($filename);
            //load json by env importance 
            return;
        }

        //fetch url
        $url = 'https://www.dailyfx.com/economic-calendar/events/';
        //todo: load from url
        //clear the unnecessary data
        //save to file
    }