<?php
  session_start();
  include("../model/access_db.php");

  #temparely
  $_SESSION['view_attendee_form'] = false;
  unset($_SESSION['attendee_form_action']);
  unset($_SESSION['modifying_attendee_name']);
  unset($_SESSION['event_info']);

  #permently part
  $event_id = $_SESSION['event_id'];
  
  if(get_event_info_from_event_id($event_id) == CODE_SUCCESS) {
    $_SESSION['event_info']['event_name'] =  $global_event_name;
    $_SESSION['event_info']['memo'] =  $global_event_memo;
    $_SESSION['event_info']['event_dates'] =  $global_event_dates;
  }else{
    add_msg("イベントが存在していない。");
    header("Location: ../index.php");
    exit();
  }

  $_SESSION['attendee_info_list'] = array();
  if(get_attendee_info_from_event_id($event_id) == CODE_SUCCESS) {
    $_SESSION['attendee_info_list']['attendee_names'] =  $global_attendee_names;
    $_SESSION['attendee_info_list']['attendee_comments'] =  $global_attendee_comments;
  }

  $_SESSION['attendee_status_list'] = array();
  foreach($global_event_dates as $event_date){

    if(get_attendee_status_from_event_id_and_event_date($event_id,$event_date) == CODE_SUCCESS){
      $_SESSION['attendee_status_list'][$event_date] =  $global_attendee_statues;
    }else{
      $_SESSION['attendee_status_list'][$event_date] = array();
    }
  }

  header( "Location: ../view_event.php?eid=".$event_id );
  exit();
?>