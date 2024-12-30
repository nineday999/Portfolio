<?php
  session_start();
  include("../model/access_db.php");

  #temparely
  $_SESSION['view_attendee_form'] = true;
  $_SESSION['attendee_form_action'] = 'post_form_modify.php';

  # form part
  $att_name = $_GET['attendee_name'];
  $_SESSION['modifying_attendee_name'] = urldecode($att_name);

  header( "Location: ../view_event.php?eid=".$_SESSION['event_id'] ); 
  exit();
?>