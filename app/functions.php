<?php


    function getYear(int $year) : string {
        $result = array();
        for ($i = 1; $i <= 12; $i++){
            $month = json_decode(getMonth($year, $i), true);
            foreach ($month as $id => $event){
                if (!isset($result[$id])) $result[$id] = $event;
            }
        }
        return json_encode($result);
    }
    
    function getMonth(int $year, int $month) : string{
        $month_str = sprintf("%02d", $month);
        $maxdays = new DateTime($year."-".$month_str."-01");
        $maxdays = $maxdays->format('t');

        $result = array();
        for ($i = 1; $i <= $maxdays; $i++){
            $day = json_decode(getDay($year, $month, $i), true);
            foreach ($day as $id => $event){
                if (!isset($result[$id])) $result[$id] = $event;
            }
        }
        return json_encode($result);
    }

    function getDay(int $year, int $month, int $day) : string{
        
        global $_setting;

        $month = sprintf("%02d", $month);
        $day = sprintf("%02d", $day);
        $date_string = $year . "-" . $month . "-" . $day;
        
        //validate date
        //too far dates are not allowed, even to persist
        $now = new DateTime();
        $asked = new DateTime($date_string);
        $interval = $now->diff($asked);
        if ($interval->format('%R') == "+" && $interval->format('%a') > 7) return "";

        //check if file exists
        $filename = "data/" . $date_string;
        if (is_file($filename)){
            return file_get_contents($filename);
        }
        
        //fetch from DailyFX
        $date_str = $year . "-" . $month . "-" . $day;
        $url = $_setting["dailyfx_url"] . "/" . $date_str;
        $client = new GuzzleHttp\Client();
        $body = "[]";
        try {
            $res = $client->request('GET', $url);
            $body = $res->getBody();
        } catch(GuzzleHttp\Exception\ClientException $e) {
            return $body;
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

        $result = json_encode($result);
        file_put_contents($filename, $result);

        return $result;
    }