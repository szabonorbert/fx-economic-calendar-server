<?php


    function getYear(int $year) : string {
        $result = "";
        for ($i = 1; $i <= 12; $i++){
            $result .= getMonth($year, $i) . ",";
        }
        $result = trim($result, ",");
        return $result;
    }
    
    function getMonth(int $year, int $month) : string{
        $month_str = sprintf("%02d", $month);
        $maxdays = new DateTime($year."-".$month_str."-01");
        $maxdays = $maxdays->format('t');

        $result = "";
        for ($i = 1; $i <= $maxdays; $i++){
            $result .= getDay($year, $month, $i) . ",";
        }
        $result = trim($result, ",");
        return $result;
    }

    //
    //  creates an invalid json, because the '[' and ']' characters are missing from the beginning and the end
    //  but this way it's simpler to persist and concatenate, we do not need to reconvert the arrays to json all the time
    //
    function getDay(int $year, int $month, int $day) : string{
        
        global $FETCH_URL;

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
        $url = $FETCH_URL . "/" . $date_str;
        $client = new GuzzleHttp\Client();
        $body = "";
        try {
            $res = $client->request('GET', $url);
            $body = $res->getBody();
        } catch(GuzzleHttp\Exception\ClientException $e) {
            //empty body is fine if something went wrong,
            //but do not persist as file
            return $body;
        }
        $body = json_decode($body, true);
        
        //load data to result array
        $result = array();
        foreach ($body as $b){
            if ($b["importanceNum"] < $_ENV["min_importance"]) continue;
            $result[] = array(
                "id" => $b["id"],
                "title" => $b["title"],
                "currency" => $b["currency"],
                "date" => $b["date"],
                "importance"=> $b["importanceNum"],
            );
        }

        $result = json_encode($result);
        $result = trim($result, "[]");  // <--- creates an invalid json, but easier and faster to concatenate the daily results
        file_put_contents($filename, $result);

        return $result;
    }