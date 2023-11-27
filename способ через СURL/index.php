<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require ('include/const.php');
require ('include/function.php');

// 2-й способ, отправка через curl

// Сообщение
$textMessage = "Новое сообщение из формы";

$getQuery = [
    "chat_id" => TG_USER_ID,
//    "text" => $textMessage,
//    "parse_mode" => "html",
//    "reply_to_message_id" => 45
    "message_id"  => 47,
];

$ch = curl_init("https://api.telegram.org/bot". TG_TOKEN ."/deleteMessage?" . http_build_query($getQuery)); //инициализируем работу

// Передаю специальные параметры для отправки запроса
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);

$resultQuery = curl_exec($ch); //Записываю сам процесс работы curl_exec - отправляет запрос и возвращает данные, которые я записываю в данную переменную.
curl_close($ch);// Закрываю соединения

echo $resultQuery;
