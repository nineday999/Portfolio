<?php 
include_once("../model/access_db.php");

session_start();

if(isset($_SESSION["event_id"])){
    $event_id = $_SESSION['event_id'];

    if(get_event_info_from_event_id($event_id) == CODE_SUCCESS){
        $event_info = [
            'event_name'=> $global_event_name,
            'event_memo'=>$global_event_memo,
            'event_dates'=>$global_event_dates]; //global_event_dates is a array object

        echo json_encode($event_info); 
    }else{
        add_msg("イベントが存在していない。");
    }

    exit();
}

if(isset($_SESSION["event_name"])
        &&isset($_SESSION["event_dates"])
        &&isset($_SESSION["event_memo"])){
            
    $event_name = $_SESSION['event_name'];
    $event_memo = $_SESSION['event_memo'];
    //event_dates in session is a text, not a array object 
    $event_dates = preg_split("/\r\n|\n|\r/", $_SESSION['event_dates']); //split string by newline for mutiple os 
       
    $event_info = [
        'event_name'=> $event_name,
        'event_memo'=>$event_memo,
        'event_dates'=>$event_dates]; //dates is a array object

    echo json_encode($event_info); 

    exit();
}


?>