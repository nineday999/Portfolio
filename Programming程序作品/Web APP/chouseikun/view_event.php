<?php 
    function show_mark($status){
        $cross = "✕";
        $tangle = "△";
        $circle = "◎";
        switch($status){
            case 0: return $cross;
            case 1: return $tangle;
            default: return $circle;
        }
    }

    if(!isset($_GET['eid'])){
        http_response_code(405);
        exit();
    }
    $event_id = $_GET['eid'];
    
    session_start();

    if(isset($_SESSION["event_id"])
        && $_SESSION["event_id"] != $event_id){
        $_SESSION["event_id"] = $event_id;
        header("Location: controller/view_attendee.php");
        exit();
    }
    $_SESSION["event_id"] = $event_id;

    if(!isset($_SESSION['event_info'])){
        header("Location: controller/view_attendee.php");
        exit();
    }

    $event_name = $_SESSION['event_info']['event_name'];
    $event_memo = $_SESSION['event_info']['memo'];
    $event_dates = $_SESSION['event_info']['event_dates'];

    if(!empty($_SESSION['attendee_info_list'])){
        $attendee_names = $_SESSION['attendee_info_list']['attendee_names'];
        $attendee_comments = $_SESSION['attendee_info_list']['attendee_comments'];
        $attendee_num = count($attendee_names);
        $attendee_status_list = $_SESSION['attendee_status_list'];
    }else{
        $attendee_num = 0;
    }

    //for creator
    $isCreator = false;
    if(isset($_COOKIE['creator_event_id_list'])){
        $event_id_list = json_decode($_COOKIE['creator_event_id_list'],true);
        if(in_array($event_id,$event_id_list)){
            $isCreator = true;
        }
    }
    //for form
    $view_attendee_form = $_SESSION['view_attendee_form'];
    if(isset($_SESSION['attendee_form_action'])){
        $attendee_form_action = $_SESSION['attendee_form_action'];
    }
    if(isset($_SESSION['modifying_attendee_name'])){
        $modifying_attendee_name = $_SESSION['modifying_attendee_name']; 
        $modifying_attendee_index = array_search($modifying_attendee_name ,$attendee_names); // return int for index,or FALSE when failed
    }else{
        $modifying_attendee_name = '';
        $modifying_attendee_index = false;
    }
?>

<!doctype html>
<html lang="ja">
    <head>
        <!-- Required meta tags -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">


        <title>出欠情報閲覧</title>
        <link rel="icon" type="image/x-icon" href="/chouseikun/template/chouseikun.png">
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    </head>

    <body class="container-fluid">

        <?php         
        include "template/navbar.html";
        
        include "template/message.php";

        include "template/event_info_and_attendee_list.php";
        ?>

        <!-- attendee enroll part -->
        <?php if ($view_attendee_form): ?>
            <?php  include "template/modify_attendee.php"; ?>
        <?php else: ?>
            <div class="mt-3 text-center">
                <a class="btn btn-primary" href="controller/create_attendee.php" role="button">出欠を入力する</a>
            </div>
        <?php endif; ?>

        </body>
</html>