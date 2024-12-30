<?php
include_once("../model/access_db.php");

session_start();

if(!isset($_SESSION["event_id"])){
    http_response_code(405);
    exit();
}
$event_id = $_SESSION["event_id"];

if(get_event_info_from_event_id($event_id) == CODE_ERROR){
    add_msg("イベントが存在していない。");
    header("Location: ../index.php");
    exit();
}

//check cookie
if(!isset($_COOKIE['creator_event_id_list'])){
    add_msg("あなたが編集権限を持っていない。");
    header("Location: ../view_event.php?eid=".$event_id);
    exit();
}
$event_id_list = json_decode($_COOKIE['creator_event_id_list'],true);
if(!in_array($event_id,$event_id_list)){
    add_msg("あなたが編集権限を持っていない。");
    header("Location: ../view_event.php?eid=".$event_id);
    exit();
}

if(delete_event_from_event_id($event_id) == CODE_SUCCESS){
    add_msg("イベントを削除した。",CODE_SUCCESS);

    $id_index = array_search($event_id,$event_id_list);
    if($id_index !== false){
        unset($event_id_list[$id_index]);
        setcookie('creator_event_id_list', json_encode($event_id_list), time()+ 3600*24*30,"/"); // 1 hour * 24 * 30 = 30 day
    }

    header("Location: ../index.php");
    exit();
}else{
    add_msg("イベントの削除は失敗した。");

    header("Location: ../view_modify_event.php?eid=".$event_id);
    exit();
}

