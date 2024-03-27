<?php
    include('connect_db.php');
    include('configurations.php');
    include('execute_functions.php');

    if ( isset($_POST['save'])) {
        $resType = $_POST['resType'];
        $fixtureId = $_POST['fixtureId'];
        $link = $_POST['link'];
        save_link_to_database($connect,$fixtureId,$resType,$link);
    }
    // close tab
    echo "<script>window.close()</script>";
?>