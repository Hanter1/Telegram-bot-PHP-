<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require ('/include/const.php');
require ('/include/function.php');


$textMessage = "Тестовое сообщение";
$textMessage = urlencode($textMessage);

$urlQuery = "https://api.telegram.org/bot". TG_TOKEN ."/sendMessage?chat_id=" . TG_USER_ID . "&text=" . $textMessage;
$result = file_get_contents($urlQuery);


// Отправляем сообщение в групповой чат, через функцию file_get_contents

$textMessage = "Тестовое сообщение";
$textMessage = urlencode($textMessage);
$urlQuery = "https://api.telegram.org/bot". TG_TOKEN ."/getUpdates";
$result = file_get_contents($urlQuery);
