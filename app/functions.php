<?php

    function loadData(int $year, int $month, int $day, array $result){
        if ($year !== null && $month !== null && $day !== null){
            loadDay($year, $month, $day);
        } else if ($year !== null && $month !== null){
            loadMonth($year, $month);
        } else if ($year !== null){
            loadYear($year);
        } else {
            die();
        }
    }

    function loadYear(int $year) {
        for ($i = 1; $i <= 12; $i++){
            loadMonth($year, $i);
        }
    }
    
    function loadMonth(int $year, int $month){
        $month_str = sprintf("%02d", $month);
        $maxdays = new DateTime($year."-".$month_str."-01");
        $maxdays = $maxdays->format('t');
        for ($i = 1; $i <= $maxdays; $i++){
            loadDay($year, $month, $i);
        }
    }

    function loadDay(int $year, int $month, int $day){
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