<?php
  session_start();
  include("../model/access_db.php");

  #temparely
  $_SESSION['view_attendee_form'] = true;
  $_SESSION['attendee_form_action'] = 'post_form.php';

  header( "Location: ../view_event.php?eid=".$_SESSION['event_id']); 
  exit();
?>