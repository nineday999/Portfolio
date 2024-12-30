<?php  if(isset($_SESSION["msg_info_list"])):?>
    <div class="bg-primary text-white rounded-4  mt-3 p-1" style="text-align:center;">
        <h2>
            <?php  
                foreach($_SESSION["msg_info_list"] as $msg){
                    echo $msg."</br>";
                }
                unset($_SESSION["msg_info_list"]);
            ?> 
        </h2>
    </div>    
<?php  endif;?>

<?php  if(isset($_SESSION["msg_error_list"])):?>
    <div class="bg-danger text-white rounded-4  mt-3 p-1" style="text-align:center;">
        <h2>
            <?php  
                $msg_list = $_SESSION["msg_error_list"];
                foreach($msg_list as $msg){
                    echo $msg."</br>";
                }

                unset($_SESSION["msg_error_list"]);
            ?>   
        </h2>
    </div>    
<?php  endif?>