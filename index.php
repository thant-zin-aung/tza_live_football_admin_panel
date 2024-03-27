<?php
    include('connect_db.php');
    include('configurations.php');
    include('execute_functions.php');

    $leagueInfoQuery = "SELECT * FROM league_info ORDER BY apiLeagueId DESC";
    $queryResult = mysqli_query($connect,$leagueInfoQuery);
    $totalLeague = mysqli_num_rows($queryResult);
    if ( $totalLeague == 0 ) {
        echo "Total league - 0\nNo league found...";
        exit();
    }

    $totalFetchedLeague = 0;

    if ( isset($_POST['fetchButton']) ) {
        for ( $leagueCount = 0 ; $leagueCount < $totalLeague ; $leagueCount++ ) {
            $leagueData = mysqli_fetch_array($queryResult);
            $apiLeagueId = $leagueData['apiLeagueId'];
            $fixtureData = get_fixture_data($API_KEY,$apiLeagueId,$currentSeason);
            if ( save_match_data_to_database($connect,$fixtureData,$apiLeagueId) ) ++$totalFetchedLeague;
        }
    }
    

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>TZA Football Live</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
        <script src="https://kit.fontawesome.com/e2c9faac31.js" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <link rel="stylesheet" href="styles/app-style.css">
    </head>
<body class="container">
    <div class="app-title">
        <span>TZA Live Football</span> - Admin Panel
        <form action="#" method="POST">
            <button class="fetch-data-button" type="submit" name="fetchButton">Fetch Data</button>
        </form>
        <div class="fetched-label"> Total Fetched Data - <?php echo $totalFetchedLeague;?></div>
    </div>



    <?php
        $queryResult = mysqli_query($connect,$leagueInfoQuery);
        for ( $leagueCount = 0 ; $leagueCount < $totalLeague ; $leagueCount++ ) {
            $leagueData = mysqli_fetch_array($queryResult);
            $apiLeagueId = $leagueData['apiLeagueId'];
            $leagueName = $leagueData['leagueName'];
            $leagueLogoPath = $leagueData['leagueLogoPath'];
            $longFromDate = get_long_from_date();
            $longToDate = get_long_to_date();
            // apiLeagueId comes from the first for loop
            // $matchInfoQuery = "SELECT * FROM match_info WHERE apiLeagueId=$apiLeagueId ORDER BY matchDate,matchTime";
            // $matchInfoQuery = "SELECT * FROM match_info WHERE ((apiLeagueId=$apiLeagueId AND matchDate='$longFromDate') OR (apiLeagueId=$apiLeagueId AND matchDate='$longToDate')) ORDER BY matchDate,matchTime";
            $matchInfoQuery = "SELECT * FROM match_info WHERE apiLeagueId=$apiLeagueId AND matchDate IN ('$longFromDate','$longToDate') ORDER BY matchDate,matchTime";
            $result = mysqli_query($connect,$matchInfoQuery);
            $totalMatches = mysqli_num_rows($result);
    ?>
    <div class="league-wrapper">
        <div class="header">
            <div class="left-wrapper">
                <div class="league-logo" style="background-image: url('<?php echo $leagueLogoPath?>');"></div>
                <div class="league-name-date">
                    <div class="name"><?php echo $leagueName.' ( '.$totalMatches.' matches )'?></div>
                    <div class="date"><?php echo get_from_date().' - '.get_to_date()?></div>
                </div>
            </div>
            <div class="right-wrapper">
                <!-- <div class="fetch-data-button">Fetch Data</div> -->
                <i class="fa-solid fa-angles-down fa-bounce down-arrow"></i>
            </div>
        </div>
        <div class="body">
            <div class="no-match-wrapper" style="display: <?php echo $totalMatches==0?'block':'none';?>;">No matches!</div>
            <?php
                for ( $matchCount = 0 ; $matchCount < $totalMatches ; $matchCount++ ) {
                    $matchData = mysqli_fetch_array($result);
                    $fixtureId = $matchData['fixtureId'];
                    $homeTeamName = $matchData['homeTeamName'];
                    $awayTeamName = $matchData['awayTeamName'];
                    $matchDate = $matchData['matchDate'];
                    $matchTime = $matchData['matchTime'];  
            ?> 
            <div class="match-wrapper" style="display: <?php echo $totalMatches==0?'none':'block';?>;">
                <div class="match-info-wrapper">
                    <div class="info-wrapper">
                        <div class="title-wrapper">
                            <?php echo $homeTeamName.' - '.$awayTeamName;?>
                            <i class="fa-solid fa-circle-check done-logo" style="display: <?php echo get_total_links_from_match($connect,$fixtureId)==6?'inline-block':'none';?>;"></i>
                        </div>
                        <div class="date-wrapper"><?php echo $matchDate.' ('.$matchTime.')';?></div>
                    </div>
                    <div class="button-wrapper">
                        <button class="add-links-button">Add Links</button>
                        <i class="fa-solid fa-angles-down fa-bounce down-arrow"></i>
                    </div>
                </div>
                <div class="add-links-wrapper">
                    <div class="voice-language-label">Stream In English</div>
                    <form action="stream_link_saver.php" method="POST" target="_blank">
                        <?php $existLink = get_link_from_database($connect,$fixtureId,'efhd'); ?>
                        <div class="link-wrapper">
                            <div class="resolution-wrapper">FHD (1920x1080)</div>
                            <input type="text" name="link" class="link-box" value="<?php echo $existLink;?>" <?php echo empty($existLink)?'':'disabled';?>>
                            <input type="text" name="fixtureId" value="<?php echo $fixtureId;?>" style="display:none;">
                            <input type="text" name="resType" value="efhd" style="display:none;">
                            <button class="btn btn-danger edit-button" name="edit">Edit</button>
                            <button class="btn btn-success save-button" type="submit" name="save" <?php echo empty($existLink)?'':'disabled';?>>Save</button>
                        </div>
                    </form>
                    <form action="stream_link_saver.php" method="POST" target="_blank">
                        <?php $existLink = get_link_from_database($connect,$fixtureId,'ehd'); ?>
                        <div class="link-wrapper">
                            <div class="resolution-wrapper">HD (1280x720)</div>
                            <input type="text" name="link" class="link-box" value="<?php echo $existLink;?>" <?php echo empty($existLink)?'':'disabled';?>>
                            <input type="text" name="fixtureId" value="<?php echo $fixtureId;?>" style="display:none;">
                            <input type="text" name="resType" value="ehd" style="display:none;">
                            <button class="btn btn-danger edit-button" name="edit">Edit</button>
                            <button class="btn btn-success save-button" type="submit" name="save" <?php echo empty($existLink)?'':'disabled';?>>Save</button>
                        </div>
                    </form>
                    <form action="stream_link_saver.php" method="POST" target="_blank">
                        <?php $existLink = get_link_from_database($connect,$fixtureId,'esd'); ?>
                        <div class="link-wrapper">
                            <div class="resolution-wrapper">SD (720x480)</div>
                            <input type="text" name="link" class="link-box" value="<?php echo $existLink;?>" <?php echo empty($existLink)?'':'disabled';?>>
                            <input type="text" name="fixtureId" value="<?php echo $fixtureId;?>" style="display:none;">
                            <input type="text" name="resType" value="esd" style="display:none;">
                            <button class="btn btn-danger edit-button" name="edit">Edit</button>
                            <button class="btn btn-success save-button" type="submit" name="save" <?php echo empty($existLink)?'':'disabled';?>>Save</button>
                        </div>
                    </form>

                    <hr>

                    <div class="voice-language-label">Stream In Non-English</div>
                    <form action="stream_link_saver.php" method="POST" target="_blank">
                        <?php $existLink = get_link_from_database($connect,$fixtureId,'nefhd'); ?>
                        <div class="link-wrapper">
                            <div class="resolution-wrapper">FHD (1920x1080)</div>
                            <input type="text" name="link" class="link-box" value="<?php echo $existLink;?>" <?php echo empty($existLink)?'':'disabled';?>>
                            <input type="text" name="fixtureId" value="<?php echo $fixtureId;?>" style="display:none;">
                            <input type="text" name="resType" value="nefhd" style="display:none;">
                            <button class="btn btn-danger edit-button" name="edit">Edit</button>
                            <button class="btn btn-success save-button" type="submit" name="save" <?php echo empty($existLink)?'':'disabled';?>>Save</button>
                        </div>
                    </form>
                    <form action="stream_link_saver.php" method="POST" target="_blank">
                        <?php $existLink = get_link_from_database($connect,$fixtureId,'nehd'); ?>
                        <div class="link-wrapper">
                            <div class="resolution-wrapper">FHD (1280x720)</div>
                            <input type="text" name="link" class="link-box" value="<?php echo $existLink;?>" <?php echo empty($existLink)?'':'disabled';?>>
                            <input type="text" name="fixtureId" value="<?php echo $fixtureId;?>" style="display:none;">
                            <input type="text" name="resType" value="nehd" style="display:none;">
                            <button class="btn btn-danger edit-button" name="edit">Edit</button>
                            <button class="btn btn-success save-button" type="submit" name="save" <?php echo empty($existLink)?'':'disabled';?>>Save</button>
                        </div>
                    </form>
                    <form action="stream_link_saver.php" method="POST" target="_blank">
                        <?php $existLink = get_link_from_database($connect,$fixtureId,'nesd'); ?>
                        <div class="link-wrapper">
                            <div class="resolution-wrapper">FHD (720x480)</div>
                            <input type="text" name="link" class="link-box" value="<?php echo $existLink;?>" <?php echo empty($existLink)?'':'disabled';?>>
                            <input type="text" name="fixtureId" value="<?php echo $fixtureId;?>" style="display:none;">
                            <input type="text" name="resType" value="nesd" style="display:none;">
                            <button class="btn btn-danger edit-button" name="edit">Edit</button>
                            <button class="btn btn-success save-button" type="submit" name="save" <?php echo empty($existLink)?'':'disabled';?>>Save</button>
                        </div>
                    </form>
                </div>
            </div>    

            <?php
                }
            ?>
        </div>
    </div>

    <?php
        }
    ?>

    

    <section id="copyright"><div>Copyright &copy; 2023 - All Rights Reserved</div></section>

    



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="scripts/app-script.js"></script>
</body>
</html>