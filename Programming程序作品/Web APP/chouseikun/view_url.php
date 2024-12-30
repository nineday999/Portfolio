<?php
    session_start();

    if(!isset($_GET['eid'])){
        http_response_code(405);
        exit;
    }

    $event_id = $_GET['eid'];
    unset($_SESSION["event_id"]);
    unset($_SESSION['event_info']);
    

    $view_event_path = "http://localhost/chouseikun/"."view_event.php";
    $view_event_url = $view_event_path."?eid=".$event_id;
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

         <!-- Bootstrap CSS -->
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

         <title>イベントURL</title>
         <link rel="icon" type="image/x-icon" href="/chouseikun/template/chouseikun.png">
    </head>

    <body  class="container-fluid">
        <?php
            include "template/navbar.html";
            include "template/message.php";
        ?>
        <div class="container">


            <!-- <div class="container-fluid m-4">
                <h1 class="fw-bold text-start">調整くん</h1>
            </div> -->
            <div class="container-fluid mt-3">
                <p class="fs-3 fw-bold border-bottom border-2 border-dark">イベント新規作成</p>
                <p>イベントが作成されました。以下のURLをメール等を使って皆に知らせてあげよう。以降、このURLページにて各自の出欠情報を入力してもらいます。</p>
            </div>
            <div class="container-fluid mt-3">
                <div class="input-group d-grid">
                    <input type="text" aria-label="link" value="<?php echo $view_event_url ; ?>" id="copyText">
                    <button class="btn btn-outline-secondary" type="button" onclick="copyTextFunction()">コピー</button>
                </div>
            </div>
            <div class="container-fluid text-center mt-3 mb-3">
                <a class="btn btn-primary" href="<?php echo $view_event_url; ?>" role="button">イベントページを表示</a>
            </div>
        </div>
    </body>
</html>

<script type="text/javascript">
    function copyTextFunction(){
        var copyText = document.getElementById("copyText");
        copyText.select();
        document.execCommand("copy");
        alert("URLがコピーされた!");
    }
</script>