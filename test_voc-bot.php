<?php
require('./libs/database/connect-db.php');
$access_token = 'n4mwBuF+8uG25l0sa3B9m6iTOARPDw2JdXvBc6DqE181CisyNbmLXoi7rT4J/gY4S3+zK5OVdXX4O1nE8iyidE/elIH2eHXxATN9dAtUGrnuEB06ZK6wXjmBDQFjoIzagGY/UtP/9XOW5RRWprrDEgdB04t89/1O/w1cDnyilFU=';
 
// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data

if (!is_null($events['events'])) {
    // Loop through each event
    foreach ($events['events'] as $event) {
        // Reply only when message sent is in 'text' format
	$text = $event['message']['text'];
	// Get replyToken
	$replyToken = $event['replyToken'];
	$hello_prefix = substr($text, 0, 10);
	$group_name = substr($text, 10);
	$source_type = $event['source']['type'];
		if($hello_prefix == "/hellobot:" AND $source_type == "group"){
			$group_id = $event['source']['groupId'];
			// $fetch_existing_group = "SELECT id FROM tbl_line_group WHERE group_id = '$group_id'";
			// $group_result = mysqli_query($conn, $fetch_existing_group);
			// if(mysqli_num_rows($group_result) > 0) {
			// 	break;
			// }

			$insert_group = "INSERT INTO tbl_line_group(group_id, group_name) VALUES('$group_id', '$group_name')";
			mysqli_query($conn, $insert_group);
			$messages = [ 'type' => 'text', 'text' => 'เปิดใช้งาน Daily Alert เรียบร้อยแล้ว'];
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
					'replyToken' => $replyToken,
					'messages' => [$messages],
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
	}
}

