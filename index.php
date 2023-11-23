<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require ('/include/const.php');
require ('/include/function.php');

// 1-й способ, через file_get_contents
/*
$textMessage = "Тестовое сообщение";
$textMessage = urlencode($textMessage);

$urlQuery = "https://api.telegram.org/bot". TG_TOKEN ."/sendMessage?chat_id=" . TG_USER_ID . "&text=" . $textMessage;
$result = file_get_contents($urlQuery);


// Отправляем сообщение в групповой чат, через функцию file_get_contents

$textMessage = "Тестовое сообщение";
$textMessage = urlencode($textMessage);
$urlQuery = "https://api.telegram.org/bot". TG_TOKEN ."/getUpdates";
$result = file_get_contents($urlQuery);
*/


// 2-й способ, отправка через curl

// Один из способов отправлять многострочное сообщение (В данном примере используется Конкатенация)
$textMessage = "строка 1 \n";
$textMessage .= "строка 2 \n";
$textMessage .= "строка 3 ";


$getQuery = [
    "chat_id" => TG_GROUP_ID,
    "text" => $textMessage,
    "parse_mode" => "html",
];

$ch = curl_init("https://api.telegram.org/bot". TG_TOKEN ."/sendMessage?" . http_build_query($getQuery)); //инициализируем работу

// Передаю специальные параметры для отправки запроса
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);

$resultQuery = curl_exec($ch); //Записываю сам процесс работы curl_exec - отправляет запрос и возвращает данные, которые я записываю в данную переменную.
curl_close($ch);// Закрываю соединения

$jsonData = json_decode($resultQuery, true);