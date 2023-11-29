<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


require ('include/const.php');
require ('include/function.php');

//Отправка ответа на сообщение
/*
$getQuery = array(
    "chat_id" 	=> TG_USER_ID,
    "text"  	=> "Новое сообщение из формы",
    "parse_mode" => "html",
    "reply_to_message_id" => 52
);
*/

// Отправка кнопок в чат
/*
$getQuery = [
    "chat_id" => TG_USER_ID,
    "text" => "Сообщение с кнопкой",
    'reply_markup' => json_encode([
        'inline_keyboard' => [
            [
                [
                    'text' => 'Button 1',
                    'callback_data' => 'test_2',
                ],
                [
                    'text' => 'Button 2',
                    'callback_data' => 'test_2',
                ],
            ]
        ],
    ])
];
*/

// Отправка клавиатуры в чат
/*
$getQuery = [
    "chat_id" => TG_USER_ID,
    "text" => "Сообщение с кнопкой",
    'reply_markup' => json_encode(array(
        'keyboard' => array(
            array(
                array(
                    'text' => 'Тестовая кнопка 1',
                    'url' => 'YOUR BUTTON URL',
                ),
                array(
                    'text' => 'Тестовая кнопка 2',
                    'url' => 'YOUR BUTTON URL',
                ),
            )),
        'one_time_keyboard' => TRUE,
        'resize_keyboard' => TRUE,
    )),
    ];
$ch = curl_init("https://api.telegram.org/bot". TG_TOKEN ."/sendMessage?" . http_build_query($getQuery)); //инициализируем работу

// Передаю специальные параметры для отправки запроса
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);

$resultQuery = curl_exec($ch); //Записываю сам процесс работы curl_exec - отправляет запрос и возвращает данные, которые я записываю в данную переменную.
curl_close($ch);// Закрываю соединения

echo $resultQuery;
*/

// Отправка изображений
/*
$arrayQuery = array(
    'chat_id' => TG_USER_ID,
    'caption' => 'Проверка работы',
    'document' => curl_file_create(__DIR__ . '/1.jpg', 'image/jpg' , '1.jpg')
);
$ch = curl_init('https://api.telegram.org/bot'. TG_TOKEN .'/sendDocument');
curl_setopt($ch, CURLOPT_POST, 1); // данный параметр говорит, что мы отправлем POST запрос,
curl_setopt($ch, CURLOPT_POSTFIELDS, $arrayQuery); // в данный параметр передаем массив с пост данными
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);
$res = curl_exec($ch); //записываем ответ от telegram
curl_close($ch);
*/

// Групповая отправка изображений и файлов
/*
$arrayQuery = [
    'chat_id' => TG_USER_ID,

    'media' => json_encode([
        ['type' => 'photo', 'media' => 'attach://1.jpg' ],
        ['type' => 'photo', 'media' => 'attach://2.jpg' ],
        ]),

    'photo' => new CURLFile(__DIR__ . '/1.jpg'),
    'photo_2' => new CURLFile(__DIR__ . '/2.jpg'),
];
$ch = curl_init('https://api.telegram.org/bot'. TG_TOKEN .'/sendDocument');
curl_setopt($ch, CURLOPT_POST, 1); // данный параметр говорит, что мы отправлем POST запрос,
curl_setopt($ch, CURLOPT_POSTFIELDS, $arrayQuery); // в данный параметр передаем массив с пост данными
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);
$res = curl_exec($ch); //записываем ответ от telegram
curl_close($ch);
*/

// работа с хуками
/*
$getQuery = [
    "url" => "https://prog-time.ru/tg_script/index.php"
];
$ch = curl_init("https://api.telegram.org/bot". TG_TOKEN ."/setWebhook?" . http_build_query($getQuery));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);

$resultQuery = curl_exec($ch);
curl_close($ch);

echo $resultQuery;
*/
/*
$data = file_get_contents('php://input'); // в данную переменную с помощью функции file_get_contents отлавливаем любые php запросы к сайту
$data = json_decode($data, true); // полученные данные в формате json декодируем 1 параметр это данные, 2-й используется для преобразование в асоциативный массив
*/


function writeLogFile($string, $clear = false){
    $log_file_name = __DIR__."/message.txt";
    if($clear == false) {
        $now = date("Y-m-d H:i:s");
        file_put_contents($log_file_name, $now." ".print_r($string, true)."\r\n", FILE_APPEND);
    }
    else {
        file_put_contents($log_file_name, '');
        $now = date("Y-m-d H:i:s");
        file_put_contents($log_file_name, $now." ".print_r($string, true)."\r\n", FILE_APPEND);
    }
}

$data = file_get_contents('php://input'); // в данную переменную с помощью функции file_get_contents отлавливаем любые php запросы к сайту
$data = json_decode($data, true); // полученные данные в формате json декодируем 1 параметр это данные, 2-й используется для преобразование в асоциативный массив
writeLogFile($data, true);

echo file_get_contents(__DIR__."/message.txt");