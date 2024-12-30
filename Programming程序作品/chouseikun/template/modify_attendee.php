<div class="row justify-content-center mt-5 mb-5">
    <div class="col-md-8">
        <div class="border-2 border-bottom border-dark">
            <p class="fs-3 fw-bold">出欠を入力する</p>
        </div>
        <form action="<?php echo "controller/".$attendee_form_action; ?>" method="POST">
            <div class="mt-3">
                <label for="attendeeName" class="form-label">名前</label>
                <div id="formatHelp" class="form-text">絵文字は使用できません。</div>
                <input type="text" class="form-control" id="attendeeName" name="attendeeName" aria-describedby="formatHelp" value="<?php echo $modifying_attendee_name; ?>">
            </div>
            <div class="mt-4"> 
                <p>日にち候補</p> 
                <div class="row g-3">
                <?php foreach ($event_dates as $date): ?>
                    <?php 
                        if($modifying_attendee_index !== FALSE
                            && array_key_exists($modifying_attendee_name,$attendee_status_list[$date])){
                            $modifying_status = $attendee_status_list[$date][$modifying_attendee_name];
                        }else{
                            $modifying_status = 2;
                        }
                    ?>
                    <div class="col-3"><?php echo $date ?></div>
                    <div class="btn-group col-9" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check" name="checkbox-<?php echo $date ?>" id="btnradio1-<?php echo $date ?>" value="2" autocomplete="off" <?php if(isset($modifying_status)&&$modifying_status ==2){echo "checked";}?>>
                        <label class="btn btn-outline-primary" for="btnradio1-<?php echo $date ?>">◎</label>

                        <input type="radio" class="btn-check" name="checkbox-<?php echo $date ?>" id="btnradio2-<?php echo $date ?>" value="1" autocomplete="off" <?php if(isset($modifying_status)&&$modifying_status == 1){echo "checked";}?>>
                        <label class="btn btn-outline-primary" for="btnradio2-<?php echo $date ?>">△</label>

                        <input type="radio" class="btn-check" name="checkbox-<?php echo $date ?>" id="btnradio3-<?php echo $date ?>" value="0" autocomplete="off" <?php if(isset($modifying_status)&&$modifying_status == 0){echo "checked";}?>>
                        <label class="btn btn-outline-primary" for="btnradio3-<?php echo $date ?>">✕</label>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
            <div class="mt-4 mb-4">
                <label for="comment" class="form-label">コメント</label>
                <input type="text" class="form-control" id="comment" name="comment" value="<?php if($modifying_attendee_index !== FALSE){echo $attendee_comments[$modifying_attendee_index];} ?>">
            </div>
            <div class="text-center">
                <?php if($modifying_attendee_index === FALSE): ?>
                    <button type="submit" class="btn btn-primary">入力する</button>
                <?php else: ?>
                    <button type="submit" class="btn btn-primary">更新する</button>
                <?php endif; ?>
                <a class="btn btn-danger" href="controller/view_attendee.php" role="button">キャンセル</a>
            </div>
        </form>
    </div>
</div>
