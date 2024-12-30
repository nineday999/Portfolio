<?php
    session_start();
    include("../model/access_db.php");

    $event_id = $_SESSION['event_id'];
    $event_dates = $_SESSION['event_info']['event_dates'];

    $input_attendeeName = $_POST['attendeeName'];
    $input_comment = $_POST['comment'];

    $input_statuses = array();
    if(create_attendee_info($event_id, $input_attendeeName, $input_comment) == CODE_SUCCESS){
        foreach($event_dates as $date){
            $input_statuses["$date"] = (int)htmlspecialchars($_POST['checkbox-'.$date.'']);
            if(create_attendee_status($event_id, $input_attendeeName, $date, $input_statuses["$date"]) == CODE_SUCCESS){

            }
        }
        add_msg("出欠情報の作成は成功した。",CODE_SUCCESS);
    }else{
        add_msg("出欠情報の作成は失敗した。");
    }

    header( "Location: ../controller/view_attendee.php" ); exit;
?>