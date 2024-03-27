<?php
    include('connect_db.php');

    // $createLeagueInfo = "CREATE TABLE league_info 
    // (
    //     apiLeagueId INT NOT NULL PRIMARY KEY,
    //     leagueName VARCHAR(150) NOT NULL,
    //     leagueLogoPath TEXT NOT NULL
    // )";

    // $query = mysqli_query($connect,$createLeagueInfo);
    // if ( $query ) {
    //     echo "<p>League Info table created in database successfully!</p>";
    // }

    // $premierId = 39;
    // $bundesligaId = 78;
    // $serieaId = 135;
    // $laligaId = 140;
    // $ligue1Id = 61;
    // $uefaChampionId = 2;
    // $uefaEuropaId = 3;
    // $englandChampionShipId = 40;
    // $englandFaId = 45;
    // $euroChampionShipId = 4;
    // $worldCupId = 1;

    // $logoPrefix = "images/league_logos/";
    // $premierLogo = $logoPrefix.'premier_league_logo.jpg';
    // $bundesligaLogo = $logoPrefix.'bundesliga_logo.png';
    // $serieaLogo = $logoPrefix.'serie_a_logo.png';
    // $laligaLogo = $logoPrefix.'la_liga_logo.png';
    // $ligue1Logo = $logoPrefix.'ligue_1_logo.jpg';
    // $uefaChampionLogo = $logoPrefix.'uefa_champion_league_logo.jpg';
    // $uefaEuropaLogo = $logoPrefix.'uefa_europa_league_logo.jpg';
    // $englandChampionshipLogo = $logoPrefix.'england_championship_logo.png';
    // $englandFaLogo = $logoPrefix.'england_fa_cup_logo.png';
    // $euroChampionshipLogo = $logoPrefix.'euro_championship_logo.jpg';
    // $worldCupLogo = $logoPrefix.'fifa_world_cup_logo.jpg';

    // $insertLeagueInfo = "INSERT INTO league_info VALUES 
    //     ($premierId,'Premier League','$premierLogo'),
    //     ($bundesligaId,'Bundeliga','$bundesligaLogo'),
    //     ($serieaId,'Serie A','$serieaLogo'),
    //     ($laligaId,'La Liga','$laligaLogo'),
    //     ($ligue1Id,'Ligue 1','$ligue1Logo'),
    //     ($uefaChampionId,'UEFA Champion League','$uefaChampionLogo'),
    //     ($uefaEuropaId,'UEFA Europa League','$uefaEuropaLogo'),
    //     ($englandChampionShipId,'England Championship','$englandChampionshipLogo'),
    //     ($englandFaId,'England Fa Cup','$englandFaLogo'),
    //     ($euroChampionShipId,'Euro Championship','$euroChampionshipLogo'),
    //     ($worldCupId,'World Cup','$worldCupLogo')
    // ";

    // $query = mysqli_query($connect,$insertLeagueInfo);
    // if ( $query ) {
    //     echo "<p>League Infos added to database successfully!</p>";
    // }


    // $createMatchInfo = "CREATE TABLE match_info 
    // (
    //     fixtureId INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    //     homeTeamName VARCHAR(100) NOT NULL,
    //     awayTeamName VARCHAR(100) NOT NULL,
    //     matchDate VARCHAR(50) NOT NULL,
    //     matchTime VARCHAR(50) NOT NULL,
    //     apiLeagueId INT NOT NULL,
    //     FOREIGN KEY(apiLeagueId) REFERENCES league_info(apiLeagueId)
    // )";

    // $query = mysqli_query($connect,$createMatchInfo);
    // if ( $query ) {
    //     echo "<p>Match Info table created in database successfully!</p>";
    // }

    // $createLinkInfo = "CREATE TABLE link_info 
    // (
    //     linkId INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    //     resType VARCHAR(30) NOT NULL,
    //     link TEXT NOT NULL,
    //     fixtureId INT NOT NULL,
    //     FOREIGN KEY(fixtureId) REFERENCES match_info(fixtureId)
    // )";

    // $query = mysqli_query($connect,$createLinkInfo);
    // if ( $query ) {
    //     echo "<p>Link Info table created in database successfully!</p>";
    // }

    
?>