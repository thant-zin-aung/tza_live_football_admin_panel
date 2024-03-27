<?php
    // include("connect_db.php");

    date_default_timezone_set("Asia/Yangon");

    function get_from_date() {
        return date("Y-m-d");
    }
    

    function get_to_date() {
        $date=date_create("Now");
        date_add($date,date_interval_create_from_date_string("1 day"));
        return date_format($date,"Y-m-d");
    }

    function get_long_from_date(){
        return date("Y F d");
    }
    function get_long_to_date() {
        $date=date_create("Now");
        date_add($date,date_interval_create_from_date_string("1 day"));
        return date_format($date,"Y F d");
    }

    function get_fixture_data($API_KEY,$leagueId,$currentSeason) {
        $fromDate = get_from_date();
        $toDate = get_to_date();
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://v3.football.api-sports.io/fixtures?league=$leagueId&season=$currentSeason&from=$fromDate&to=$toDate&timezone=Asia/Yangon",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "X-RapidAPI-Host: api-football-beta.p.rapidapi.com",
                "X-RapidAPI-Key: $API_KEY"
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $fixtureDataArray = json_decode($response,true);
            return $fixtureDataArray;
        }
    }

    function get_team_name($fixtureData,$fixtureId,$isHome) {
        for ( $count = 0 ; $count < count($fixtureData['response']) ; $count++ ) {
            if ( $fixtureData['response'][$count]['fixture']['id'] == $fixtureId ) {
                if ( $isHome ) return $fixtureData['response'][$count]['teams']['home']['name'];
                else return $fixtureData['response'][$count]['teams']['away']['name'];
            }
        }
        return "";
    }

    function is_match_live($shortStatus) {
        return $shortStatus=="1H" || $shortStatus=="1H" || $shortStatus=="HT" || $shortStatus=="2H" || $shortStatus=="ET" || 
                $shortStatus=="BT" || $shortStatus=="P" || $shortStatus=="SUSP" || $shortStatus=="INT";
    }
    function is_match_finished($shortStatus) {
        return $shortStatus=="FT" || $shortStatus=="AET" || $shortStatus=="PEN";
    }

    function get_match_start_date_or_time($fixtureData,$fixtureId,$isTime) {
        for ( $count = 0 ; $count < count($fixtureData['response']) ; $count++ ) {
            if ( $fixtureData['response'][$count]['fixture']['id'] == $fixtureId ) {
                $timestamp = $fixtureData['response'][$count]['fixture']['timestamp'];
                if ( $isTime ) {
                    return  date("h:i a",$timestamp);
                }
                else return date('Y F d', $timestamp);
            }
        }
        return "NaN";
    }

    function get_fixture_id($fixtureData,$responseIndex) {
        return $fixtureData['response'][$responseIndex]['fixture']['id'];
    }
    function get_api_total_matches($fixtureData) {
        return count($fixtureData['response']);
    }

    function save_match_data_to_database($connect,$fixtureData,$apiLeagueId) {
        $isCompleteSave=true;
        for ($count=0 ; $count<get_api_total_matches($fixtureData); $count++) {
            $fixtureId = get_fixture_id($fixtureData,$count);
            $homeTeamName = get_team_name($fixtureData,$fixtureId,true);
            $awayTeamName = get_team_name($fixtureData,$fixtureId,false);
            $matchDate = get_match_start_date_or_time($fixtureData,$fixtureId,false);
            $matchTime = get_match_start_date_or_time($fixtureData,$fixtureId,true);
            $insertQuery = "INSERT INTO match_info(fixtureId,homeTeamName,awayTeamName,matchDate,matchTime,apiLeagueId) 
                VALUES ($fixtureId,'$homeTeamName','$awayTeamName','$matchDate','$matchTime',$apiLeagueId)";
            $queryResult = mysqli_query($connect,$insertQuery);
            if(!$isCompleteSave) {
                continue;
            } else {
                $isCompleteSave = $queryResult;
            }
        }

        return $isCompleteSave;
    }

    function save_link_to_database($connect,$fixtureId,$resType,$link) {
        $checkExistQuery = "SELECT * FROM link_info WHERE (fixtureId=$fixtureId AND resType='$resType')";
        $updateQuery = "UPDATE link_info SET link='$link' WHERE (fixtureId=$fixtureId AND resType='$resType')";
        $insertQuery = "INSERT INTO link_info(resType,link,fixtureId) 
            VALUES ('$resType','$link',$fixtureId)";

        $checkExistQueryResult = mysqli_query($connect,$checkExistQuery);
        $checkExistCount = mysqli_num_rows($checkExistQueryResult);
        if ( $checkExistCount > 0 ) {
            $updateQueryResult = mysqli_query($connect,$updateQuery);
            return $insertQueryResult;
        } else {
            $insertQueryResult = mysqli_query($connect,$insertQuery);
            return $insertQueryResult;
        }
    }

    function get_link_from_database($connect,$fixtureId,$resType) {
        $selectQuery = "SELECT * FROM link_info WHERE (fixtureId=$fixtureId AND resType='$resType')";
        $queryResult = mysqli_query($connect,$selectQuery);
        $resultCount = mysqli_num_rows($queryResult);
        if ( $resultCount==0 ) {
            return "";
        } else {
            $queryData = mysqli_fetch_array($queryResult);
            $link = $queryData['link'];
            return $link;
        }
    }

    function get_total_matches_from_league($connect,$apiLeagueId) {
        $selectQuery = "SELECT * FROM match_info WHERE apiLeagueId=$apiLeagueId";
        $queryResult = mysqli_query($connect,$selectQuery);
        $totalMatches = mysqli_num_rows($queryResult);
        return $totalMatches;
    }

    function get_total_links_from_match($connect,$fixtureId) {
        $selectQuery = "SELECT * FROM link_info WHERE fixtureId=$fixtureId";
        $queryResult = mysqli_query($connect,$selectQuery);
        $resultCount = mysqli_num_rows($queryResult);
        return $resultCount;
    }

    

?>