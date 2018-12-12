<?php
  $access_token = 'n4mwBuF+8uG25l0sa3B9m6iTOARPDw2JdXvBc6DqE181CisyNbmLXoi7rT4J/gY4S3+zK5OVdXX4O1nE8iyidE/elIH2eHXxATN9dAtUGrnuEB06ZK6wXjmBDQFjoIzagGY/UtP/9XOW5RRWprrDEgdB04t89/1O/w1cDnyilFU=';
  $messages = [ 'type' => 'text', 'text' => "Hello, this is push message."];
  $url = 'https://api.line.me/v2/bot/message/push';
  $data = [
      'to' => "Ube5f4c7c18af1e4536d7b9bf6b7c15d1",
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