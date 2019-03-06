<?php
    require('./libs/database/connect-db.php');
    require('./libs/utils/date_thai.php');
    require('./libs/utils/date_utils.php');
    require('./libs/utils/messages.php');
    $access_token = 'n4mwBuF+8uG25l0sa3B9m6iTOARPDw2JdXvBc6DqE181CisyNbmLXoi7rT4J/gY4S3+zK5OVdXX4O1nE8iyidE/elIH2eHXxATN9dAtUGrnuEB06ZK6wXjmBDQFjoIzagGY/UtP/9XOW5RRWprrDEgdB04t89/1O/w1cDnyilFU=';
    
    $todaytime = strtotime('today');
    $todaydate = date('Y-m-d', $todaytime);
    $fetch_holiday = "SELECT * FROM tbl_holiday WHERE status = 'A' AND holiday_date = '$todaydate'";
    $holiday_list = mysqli_query($conn, $fetch_holiday);

    if(isWeekend($todaydate) || mysqli_num_rows($holiday_list) > 0){
        return;
    }
//Alert 10 Days
    $fetch_group_list = "SELECT group_id FROM tbl_line_group WHERE status = 'A' AND alert_7days = 'N'";
    $group_list = mysqli_query($conn, $fetch_group_list);

    $fetch_existing_complaint = "SELECT main_office, COUNT(main_office) AS count_complaint ".
                                "FROM tbl_complaint ".
                                "WHERE number_of_day>='10' AND complaint_status <> 'ปิด' ".
                                "GROUP BY main_office ".
                                "HAVING COUNT(main_office) > 0 ".
                                "ORDER BY main_office ASC";
    $complaint_list = mysqli_query($conn, $fetch_existing_complaint);
    if(mysqli_num_rows($complaint_list) > 0){
        $messages = getBubbleMessages($conn, DateThai(date("Y-m-d")), $complaint_list);
    } else {
        $messages = [
            "type"=> "text",
            "text"=> "Daily Alert :\n\nไม่มีข้อร้องเรียนสถานะกำลังดำเนินการหรือรอดำเนินการที่มากกว่าเท่ากับ 10 วัน ในวันที่ ".DateThai(date("Y-m-d"))
        ];
    }

    while($group = $group_list->fetch_assoc()){
        $url = 'https://api.line.me/v2/bot/message/push';
        $data = [
            'to' => $group['group_id'],
            'messages' => [$messages]
        ];
        $post = json_encode($data);
        $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
    }
//Alert 7 Days
    $fetch_group_list = "SELECT group_id FROM tbl_line_group WHERE status = 'A' AND alert_7days = 'Y'";
    $group_list = mysqli_query($conn, $fetch_group_list);

    $fetch_existing_complaint = "SELECT main_office, COUNT(main_office) AS count_complaint ".
                                "FROM tbl_complaint ".
                                "WHERE office_code like 'H%' AND number_of_day>='7' AND complaint_status <> 'ปิด' ".
                                "GROUP BY main_office ".
                                "HAVING COUNT(main_office) > 0 ".
                                "ORDER BY main_office ASC";
    $complaint_list = mysqli_query($conn, $fetch_existing_complaint);
    if(mysqli_num_rows($complaint_list) > 0){
        $messages = getBubbleMessages($conn, DateThai(date("Y-m-d")), $complaint_list);
    } else {
        $messages = [
            "type"=> "text",
            "text"=> "Daily Alert :\n\nไม่มีข้อร้องเรียนสถานะกำลังดำเนินการหรือรอดำเนินการที่มากกว่าเท่ากับ 7 วัน ในวันที่ ".DateThai(date("Y-m-d"))
        ];
    }

    while($group = $group_list->fetch_assoc()){
        $url = 'https://api.line.me/v2/bot/message/push';
        $data = [
            'to' => $group['group_id'],
            'messages' => [$messages]
        ];
        $post = json_encode($data);
        $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
    }
    