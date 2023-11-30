<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require ('include/const.php');
require ('include/function.php');


/* Проверка на ответ при нажатии на кнопки */

if($arrDataAnswer["callback_query"]) {
    $dataBut = $arrDataAnswer["callback_query"]["data"];
    $textMessage = mb_strtolower($arrDataAnswer["callback_query"]["message"]["text"]);
    $chatId = $arrDataAnswer["callback_query"]["message"]["chat"]["id"];

    if($dataBut == "but_1") {
        $arrayQuery = array(
            'chat_id' => $chatId,
            'text' => "Ты нажал на 'КНОПКА 1'",
            'parse_mode' => "html",
        );
        TG_sendMessage($arrayQuery);
    }
    else if($dataBut == "but_2") {
        $arrayQuery = array(
            'chat_id' => $chatId,
            'text' => "Ты нажал на 'КНОПКА 2'",
            'parse_mode' => "html",
        );
        TG_sendMessage($arrayQuery);
    }
}

if($textMessage == 'привет') {
    $textMessage_bot = "Привет! Есть фото для меня?";

    $arrayQuery = array(
        'chat_id' 		=> $chatId,
        'text'			=> $textMessage_bot,
        'parse_mode'	=> "html",
    );
    TG_sendMessage($arrayQuery);
} else if($textMessage == 'хочу фото') {
    $textMessage_bot = "Вот, держи!";

    $listFile = list_files(__DIR__ . "/img/");

    $max = count($listFile) - 1;
    $randIdFile = rand(0, $max);

    $filePath = __DIR__ . "/img/" . $listFile[$randIdFile];

    $arrayQuery = array(
        'chat_id' => $chatId,
        "photo" => new CURLFile($filePath),
        "caption" => $textMessage_bot
    );
    TG_sendPhoto($arrayQuery);

}

/* Функционал по отправке кнопок*/

else if($textMessage == 'отправь кнопки') {
    $textMessage_bot = "Вот твои кнопки! Нажимай";

    $arrayQuery = array(
        'chat_id' => $chatId,
        'text'	=> $textMessage_bot,
        'parse_mode'	=> "html",
        'reply_markup' => json_encode(array(
            'inline_keyboard' => array(
                array(
                    array(
                        'text' => 'Кнопка 1',
                        'callback_data' => 'but_1',
                    ),

                    array(
                        'text' => 'Кнопка 2',
                        'callback_data' => 'but_2',
                    )
                ),
            ),
        )),
    );
    TG_sendMessage($arrayQuery);
}

/* Отлавливаем отправленное изображения от пользователя */

if(!empty($documentData)) {

    $arrayQuery = array(
        "file_id" => $documentData["file_id"],
    );
    $resultQuery = TG_getFile($arrayQuery);

    /* записываем ответ в формате PHP массива */
    $arrDataResult = json_decode($resultQuery, true);

    /* записываем URL необходимого изображения */
    $fileUrl = $arrDataResult["result"]["file_path"];

    /* формируем полный URL до файла */
    $photoPathTG = "https://api.telegram.org/file/bot". TG_TOKEN ."/" . $fileUrl;

    /* забираем название файла */
    $arrFilePath = explode("/", $fileUrl);
    $newFilerPath = __DIR__ . "/img/" . $arrFilePath[1];

    /* сохраняем файл на сервер */
    file_put_contents($newFilerPath , file_get_contents($photoPathTG));

    $arrayQuery = array(
        'chat_id' => $chatId,
        'text' => "Отличное фото! Я его, пожалуй, сохраню",
        'parse_mode' => "html",
    );
    TG_sendMessage($arrayQuery);
}