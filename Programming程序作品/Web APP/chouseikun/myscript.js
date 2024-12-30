// get_event_info();

function get_event_info(){
    $.ajax({
        url: 'controller/check_event.php',
        method: 'POST',
        dataType: "json",
        success: function(event_info) {
            window.console.log(event_info);
            
            // #dates is a array object
            setup_event_info(event_info.event_name,event_info.event_memo,event_info.event_dates)
        },
        error: function(error) {
            window.console.log("get_event_info error");
            // window.console.log(error);
            return_homepage();            
        }
    });
}

// #dates is a array object
function setup_event_info(name,memo,dates){
    elm_name = document.querySelector('#name');
    elm_memo = document.querySelector('#memo');
    elm_dates = document.querySelector('#dates');
    elm_submit = document.querySelector('#submit');

    elm_name.value = name;
    elm_memo.value = memo;
    elm_submit.value = "更新する"

    dates.forEach(date => {
        elm_dates.value += date+"\r\n";
    });
}

function return_homepage(){
    if(window.location.pathname != "/chouseikun/index.php"){
        window.location.href = "index.php";
    }
}

function setup_msg(msg){
    test();
    elm_msg = document.querySelector('#msg');
    elm_msg.value = msg;
}

function testMsg(msg)
{
    window.alert(msg);
}
function test()
{
    testMsg('Go');
}

