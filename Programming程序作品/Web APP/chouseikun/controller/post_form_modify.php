<?php
    session_start();
    include("../model/access_db.php");

    $event_id = $_SESSION['event_id'];
    $event_dates = $_SESSION['event_info']['event_dates'];
    $old_attendee_name = $_SESSION['modifying_attendee_name'];

    $input_attendeeName = $_POST['attendeeName'];
    $input_comment = $_POST['comment'];
    
    $input_statuses = array();
    if(modify_attendee_info_from_event_id_and_attendee_name($event_id,$old_attendee_name,$input_attendeeName,$input_comment) == CODE_SUCCESS){
        foreach($event_dates as $date){
            $input_statuses["$date"] = (int)htmlspecialchars($_POST['checkbox-'.$date.'']);
            if(modify_attendee_status_from_event_id_and_attendee_name($event_id,$old_attendee_name,$input_attendeeName,$date, $input_statuses["$date"]) == CODE_SUCCESS){

            }
        }
        add_msg("出欠情報の編集は成功した。",CODE_SUCCESS);
    }else{
        add_msg("出欠情報の編集は失敗した。");
    }

    header( "Location: ../controller/view_attendee.php" ); 
    exit();
?>