<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


require ('include/const.php');
require ('include/function.php');

// Отправка кнопок в чат
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

*/

$ch = curl_init("https://api.telegram.org/bot". TG_TOKEN ."/sendMessage?" . http_build_query($getQuery)); //инициализируем работу

// Передаю специальные параметры для отправки запроса
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);

$resultQuery = curl_exec($ch); //Записываю сам процесс работы curl_exec - отправляет запрос и возвращает данные, которые я записываю в данную переменную.
curl_close($ch);// Закрываю соединения

echo $resultQuery;