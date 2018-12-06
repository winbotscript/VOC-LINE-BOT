<?php
	//คัดลอก Channel access token จากหน้าตั้งค่าของเว็บไซต์ developer.line.me/en
	$accessToken = "n4mwBuF+8uG25l0sa3B9m6iTOARPDw2JdXvBc6DqE181CisyNbmLXoi7rT4J/gY4S3+zK5OVdXX4O1nE8iyidE/elIH2eHXxATN9dAtUGrnuEB06ZK6wXjmBDQFjoIzagGY/UtP/9XOW5RRWprrDEgdB04t89/1O/w1cDnyilFU=";

	// ดึงข้อมูลที่ LINE Server ส่งมาทั้งหมด เก็บในตัวแปร $payloads ด้วยประเภทตัวแปร String
	$payloads = file_get_contents("php://input");
	
	// เปลี่ยนประเภทตัวแปรของ $payloads จากประเภท String เป็น Object เพื่อให้สามารถดึงค่าได้
	$json = json_decode($payloads, true);
	
	// ดึงข้อความที่ผู้ใช้พิมพ์คุยกับโปรแกรมอัตโนมัติของเรา
	$message = $json['events'][0]['message']['text'];
	
	/*
	 *  กำหนดรูปการตอบกลับไปยัง LINE Server โดยมีค่าสองรูปแบบดังนี้
	 *   1. กำหนดค่า Content-Type เป็น application/json 
	 *   2. กำหนดค่า Authorization เป็น Bearer ${ข้อความพิเศษที่ดึงจาก Channel access token}
	*/
	$arrayHeader = array();
	$arrayHeader[] = "Content-Type: application/json";
	$arrayHeader[] = "Authorization: Bearer $accessToken";

	// กำหนดรูปแบบของข้อมูลในการส่งกลับไปยังผู้ใช้งาน LINE ที่พิมพ์โต้ตอบกับโปรแกรมอัตโนมัติ
	$arrayPostData['replyToken'] = $json['events'][0]['replyToken'];
	$arrayPostData['messages'][0]['type'] = "text";
	$arrayPostData['messages'][0]['text'] = $message;

	// เรียกฟังก์ชั่น replyMsg เพื่อส่งข้อความตอบกลับ
	replyMsg($arrayHeader, $arrayPostData);

	function replyMsg($arrayHeader, $arrayPostData){
		$actionURL = "https://api.line.me/v2/bot/message/reply";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $actionURL);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $arrayHeader);    
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrayPostData));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($ch);
		curl_close($ch);
	}

	exit;
	
?>