<?php 
session_start();

//reset session form view event both [index.php] and [view_modify_event.php]
unset($_SESSION["event_id"]);
unset($_SESSION['event_info']);
unset($_SESSION['attendee_info_list']);
unset($_SESSION['attendee_status_list']);
unset($_SESSION['view_attendee_form']);
unset($_SESSION['attendee_form_action']);
unset($_SESSION['modifying_attendee_name']);

if(isset($_GET['eid'])){//modify mode
    $event_id = $_GET['eid'];
    
    //check cookie
    if(!isset($_COOKIE['creator_event_id_list'])){
        $_SESSION['msg_error_list'][]="あなたは編集の権限を持ってない。";
        header("Location: view_event.php?eid=".$event_id);
        exit();
    }

    $event_id_list = json_decode($_COOKIE['creator_event_id_list'],true);
    if(!in_array($event_id,$event_id_list)){
        $_SESSION['msg_error_list'][]="あなたは編集の権限を持ってない。";
        header("Location: view_event.php?eid=".$event_id);
        exit();
    }
    $_SESSION["event_id"] = $event_id;

    $return_path = "view_event.php?eid=".$event_id;
}else{//create mode
    $return_path = "index.php";
}
?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php
            if(isset($_SESSION["event_id"])){
                echo "イベント編集";
            }else{
                echo "イベント作成";
            }
        ?>
    </title>
    <link rel="icon" type="image/x-icon" href="/chouseikun/template/chouseikun.png">
    <!--CSS-->
    <link rel="stylesheet" type="text/css" href="mystyle.css">
    <!-- CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://code.jquery.com/jquery-1.12.4.js" integrity="sha256-Qw82+bXyGq6MydymqBxNPYTaUXXq7c8v3CwiYwLLNXU=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js" integrity="sha256-J8ay84czFazJ9wcTuSDLpPmwpMXOm573OUtZHPQqpEU=" crossorigin="anonymous"></script>
</head>

<body class="container-fluid">
    <?php
    include "template/navbar.html";

    include "template/message.php";

    include "template/modify_event.html";
    ?>
    <?php if(isset($_SESSION["event_id"])):?>

        <div class="float-end mx-4 mb-4">
            <button class="btn btn-outline-danger mx-2" onclick="deleteEvent()">削除</button>
            <button class="btn btn-outline-primary" onclick="location.href='<?php echo $return_path ?>'">戻る</button>
        </div>
    <?php endif; ?>
    
</body>
</html>

<!-- CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/locales/bootstrap-datepicker.nl.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.standalone.min.css" rel="stylesheet"/>

<!--Script-->
<script type="text/javascript" src="myscript.js"></script>
<script type="text/javascript">
    get_event_info(); // function from myscript.js

    $('#datepicker').datepicker({
            todayHighlight: true,
            format: 'yyyy/mm/dd',
    });
    $('#datepicker').on('changeDate', function() {
        $('#dates').val($('#dates').val()+$('#datepicker').datepicker('getFormattedDate')+'\r\n');
    });

    function deleteEvent() {
        if (confirm("本当にイベントを削除してもよいのか")) {
            window.location.href = "controller/delete_event.php";
        }else{
            
        }
    }
</script>
