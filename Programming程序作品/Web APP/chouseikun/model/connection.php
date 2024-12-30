<?php
$dsn = 'mysql:dbname=chouseikun;host=localhost;charset=utf8;';
try{
    $GLOBALS["pdo"] = new PDO($dsn,'root','kickickic');
}catch(PDOException $e){
    return add_msg('Failed to connect to MySQL:'.$e->getMessage());
}

function add_msg($msg,$error_code = CODE_ERROR){
    if($error_code == CODE_SUCCESS){
        $_SESSION['msg_info_list'][]=$msg;
    }else if($error_code == CODE_ERROR){
        $_SESSION['msg_error_list'][]=$msg;
    }    
}
?>