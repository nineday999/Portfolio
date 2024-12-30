<?php
include_once("../model/access_db.php");

session_start();

$redirect_location = "index.php"; // default page

if(!isset($_POST["event_name"])
    || !isset($_POST["event_dates"])
    || !isset($_POST["event_memo"])){

    http_response_code(405);
    exit();
}

$event_name = $_POST['event_name'];
$event_dates = $_POST['event_dates'];
$event_memo = $_POST['event_memo'];

$event_dates = rtrim($event_dates);

//modify event when holding a event id, otherwise create a new event.
if(isset($_SESSION["event_id"])){
    $event_id = $_SESSION['event_id'];

    if(get_event_info_from_event_id($event_id) == CODE_ERROR){
        add_msg("イベントが存在していない。");
        header("Location: ../".$redirect_location);
        exit();
    }

    if(modify_event($event_id,$event_name,$event_dates,$event_memo) == CODE_SUCCESS){
        add_msg("イベントの編集が成功した。",CODE_SUCCESS);

        //redirect to view event page
        $redirect_location="view_event.php?eid=".$event_id;
    }else{
        add_msg("イベントの編集が失敗した。");
        //return modify event page
        $redirect_location="view_modify_event.php?eid=".$event_id;
    }
}else{     
    if(create_event($event_name,$event_dates,$event_memo) == CODE_SUCCESS){
        add_msg("イベントの作成は成功した。",CODE_SUCCESS);

        //unset data in session when success
        unset($_SESSION["event_name"]);
        unset($_SESSION["event_dates"]);
        unset($_SESSION["event_memo"]);

        //set cookie
        if(isset($_COOKIE['creator_event_id_list'])){
            $event_id_list = json_decode($_COOKIE['creator_event_id_list'],true);
        }else{
            $event_id_list = array();
        }
        // if(!in_array($event_id,$event_id_list)){
            $event_id_list[] = $global_event_id;
        // }
        setcookie('creator_event_id_list', json_encode($event_id_list), time()+ 3600*24*30,"/"); // 1 hour * 24 * 30 = 30 day

        //redirect to view url page
        $redirect_location = "view_url.php?eid=".$global_event_id;
    }else{
        add_msg("イベントの作成は失敗した。もう一度試してみてください。");
        //keep data in session until success
        //if have any problem, return back and setup form fields by read session in check_event.php 
        $_SESSION["event_name"]=$event_name;
        $_SESSION["event_dates"]=$event_dates;
        $_SESSION["event_memo"]=$event_memo;

        //return to default page for create event
        $redirect_location = "index.php";
    }
}

header("Location: ../".$redirect_location);
exit();

?>