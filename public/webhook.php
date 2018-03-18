<?php

$TOKEN = "569421604:AAGv8ZCrjpuZrTDTmrNdF2ZicvG8WrkVeGE";
$TELEGRAM = "https://api.telegram.org:443/bot$TOKEN"; 
$chatId = "549328634";

if (checkToken()) 
{
  $request = receiveRequest();
  $chatId = $request->message->chat->id;
  $text = $request->message->text;
  sendMessage($chatId, $text);
}

function checkToken() 
{
  global $TOKEN;
  $pathInfo = ltrim($_SERVER['PATH_INFO'] ?? '', '/');
  return $pathInfo == $TOKEN;
}

function receiveRequest() 
{
  $json = file_get_contents("php://input");
  $request = json_decode($json, $assoc=false);
  return $request;
}

function sendMessage($chatId, $text) 
{
  global $TELEGRAM;	

  $query = http_build_query([
    'chat_id'=> $chatId,
    'text'=> $text,
    'parse_mode'=> "Markdown", 
  ]);

  $response = file_get_contents("$TELEGRAM/sendMessage?$query");
  return $response;
}

if (count(get_included_files()) <= 1){
    $request = file_get_contents("php://input");

    $date = date('Y-m-d H:i:s');
    
    $content = "$date\n$request\n\n";
    
    file_put_contents("webhook.log", $content, FILE_APPEND);
    
    $first_name = $request->message->from->first_name;
    $text = $request->message->text;
    
    $chat = fopen("chatdata.txt", "a");
    $data="<b>".$first_name.':</b> '.$text."<br>";
    fwrite($chat,$data);
    fclose($chat);
}



