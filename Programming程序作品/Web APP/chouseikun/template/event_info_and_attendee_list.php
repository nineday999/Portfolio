<div class="container">
    <!-- event name part -->
    <div class="container-fluid mt-3">
        <div class="row border-2 border-bottom border-dark">
            <div class="col-md-8">
                <p class="fs-3 fw-bold"><?php echo $event_name; ?></p>
            </div>
            <?php if( $isCreator == True ) : ?>
                <div class="col-md-4 text-end">
                    <a class="btn btn-outline-primary" href="<?php echo "view_modify_event.php?eid=".$event_id; ?>" role="button">
                        イベント編集
                    </a>
                </div>
            <?php endif ?>
        </div>
        <div>
            <p class="text-start pt-1 fs-5">
                <?php 
                  if($isCreator){
                    echo "解答者数{$attendee_num}人、 あなたがイベントの幹事です。";
                  }else{
                    echo "解答者数{$attendee_num}人、 「出欠を入力する」ボタンから出欠を入力しましょう。";
                  }
                ?>
            </p> 
        </div>
    </div>
    
    <!-- event memo part -->
    <div class="container-fluid mt-5">
        <div>
            <p class="fs-3 fw-bold">イベントの詳細説明</p>
        </div>
        <div>
            <p class="text-start pt-1 fs-5"><?php echo $event_memo; ?></p> 
        </div>
    </div>

    <!-- schedule part -->
    <div class="container-fluid mt-5">
        <div>
            <p class="fs-3 fw-bold">日にち候補</p>
        </div>
        <div>
            <p class="text-start pt-1 fs-5">※各自の出欠状況を変更するには名前のリンクをクリックしてください。</p> 
        </div>
        <table class="table table-bordered border-dark text-center">
            <thead>
                <tr>
                    <th scope="col">日程</th>
                    <th scope="col">◎</th>
                    <th scope="col">△</th>
                    <th scope="col">✕</th>
                    <?php if($attendee_num > 0): ?>
                        <?php foreach ($attendee_names as $attendee_name): ?>
                            <th scope="col">
                            <a class="btny" href="controller/modify_attendee.php?attendee_name=<?php echo urlencode($attendee_name); ?>" role="button">
                                <?php echo $attendee_name; ?>
                            </a>
                            </th>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                    $cross_num = [];
                    $tangle_num = [];
                    $circle_num = [];
                    foreach ($event_dates as $date){
                        $cross_num[$date] = 0;
                        $tangle_num[$date] = 0;
                        $circle_num[$date] = 0;
                        if($attendee_num > 0){
                            foreach ($attendee_status_list[$date] as $status) {
                                switch ($status) {
                                    case 0: $cross_num[$date]++; break;
                                    case 1: $tangle_num[$date]++; break;
                                    case 2: $circle_num[$date]++; break;
                                    default: break;
                                }
                            }
                        }
                    }
                    unset($dates_of_max_circle);
                    $dates_of_max_circle = array_keys($circle_num,max($circle_num));
                    $tangle_of_max_circle = [];
                    if(count($dates_of_max_circle) != 1){
                        foreach($dates_of_max_circle as $date_of_circle){
                            $tangle_of_max_circle[$date_of_circle] = $tangle_num[$date_of_circle]; 
                        }
                        $highlight_dates = array_keys($tangle_of_max_circle, max($tangle_of_max_circle));
                    }else{
                        $highlight_dates = $dates_of_max_circle;
                    }
                    // print_r($highlight_dates);
                ?>
                <?php foreach ($event_dates as $date): ?>
                <tr <?php if(array_search($date, $highlight_dates)!==false){echo 'class="table-active"';} ?>>
                    <td><?php echo $date; ?></td>
                    <td><?php echo $circle_num[$date]; ?></td>
                    <td><?php echo $tangle_num[$date]; ?></td>
                    <td><?php echo $cross_num[$date]; ?></td>
                    <?php 
                        if($attendee_num > 0){
                            foreach ($attendee_names as $attendee_name){
                                if(array_key_exists($attendee_name,$attendee_status_list[$date])){
                                    $mark = show_mark($attendee_status_list[$date][$attendee_name]);
                                    echo "<td>".$mark."</td>";
                                }else{
                                    echo "<td></td>";
                                }
                            }
                        }
                    ?>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td>コメント</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <?php if($attendee_num > 0): ?>
                        <?php foreach ($attendee_comments as $comment): ?>
                            <td><?php echo $comment; ?></td>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tr>
            </tbody>
        </table>
    </div>
</div>
