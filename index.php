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
