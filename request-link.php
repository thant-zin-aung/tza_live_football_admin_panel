<?php
    include('connect_db.php');
    include('configurations.php');
    include('execute_functions.php');

    $fixtureId = $_GET['fixtureId'];
    $date = $_GET['date'];
    $date = str_replace("-"," ",$date);
    $resType = $_GET['resType'];

    $selectQuery = "SELECT m.fixtureId,m.matchDate,l.resType,l.link FROM match_info as m INNER JOIN link_info as l ON m.fixtureId=l.fixtureId WHERE (m.matchDate='$date' AND l.fixtureId=$fixtureId AND l.resType='$resType')";
    $selectQueryResult = mysqli_query($connect,$selectQuery);
    $queryData = mysqli_fetch_array($selectQueryResult);
    $streamLink = $queryData['link'];
    $jsonData = [ 'streamLink' => $streamLink ];
    
    header('Content-type: application/json');
    echo json_encode( $jsonData );
?>