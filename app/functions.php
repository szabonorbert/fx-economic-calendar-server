<?php

    function getYears(int $y1, int $y2) : array{
        $result = array();
        for ($i = $y1; $i <= $y2; $i++){
            $year = getYear($i);
            foreach ($year as $id => $event){
                if (!isset($result[$id])) $result[$id] = $event;
            }
        }
        return $result;
    }

    function getYear(int $year) : array{
        $result = array();
        for ($i = 1; $i <= 12; $i++){
            $month = getMonth($year, $i);
            foreach ($month as $id => $event){
                if (!isset($result[$id])) $result[$id] = $event;
            }
        }
        return $result;
    }
    
    function getMonth(int $year, int $month) : array{
        $month_str = sprintf("%02d", $month);
        $maxdays = new DateTime($year."-".$month_str."-01");
        $maxdays = $maxdays->format('t');

        $result = array();
        for ($i = 1; $i <= $maxdays; $i++){
            $day = getDay($year, $month, $i);
            foreach ($day as $id => $event){
                if (!isset($result[$id])) $result[$id] = $event;
            }
        }
        return $result;
    }

    function getDay(int $year, int $month, int $day) : array{
        
        global $_setting;

        $month = sprintf("%02d", $month);
        $day = sprintf("%02d", $day);
        $date_string = $year . "-" . $month . "-" . $day;
        
        //the result
        $result = array();

        //validate date
        //too far dates are not allowed, even to persist
        $now = new DateTime();
        $asked = new DateTime($date_string);
        $interval = $now->diff($asked);
        if ($interval->format('%R') == "+" && $interval->format('%a') > 7) return $result;

        //check if file exists
        $filename = "data/" . $date_string;
        if (is_file($filename)){
            return json_decode(file_get_contents($filename), true);
        }
        
        //fetch from DailyFX
        $date_str = $year . "-" . $month . "-" . $day;
        $url = $_setting["dailyfx_url"] . "/" . $date_str;
        $client = new GuzzleHttp\Client();
        
        try {
            $res = $client->request('GET', $url);
            $body = $res->getBody();
        } catch(GuzzleHttp\Exception\ClientException $e) {
            return $result;
        }

        $body = json_decode($body, true);
        
        //load data to result array
        $result = array();
        foreach ($body as $b){
            if ($b["importanceNum"] < $_setting["min_importance"]) continue;
            if (isset($result[$b["id"]])) continue;
            $result[$b["id"]] = array(
                "title" => $b["title"],
                "currency" => $b["currency"],
                "date" => $b["date"],
                "importance"=> $b["importanceNum"],
            );
        }

        file_put_contents($filename, json_encode($result));
        return $result;
    }