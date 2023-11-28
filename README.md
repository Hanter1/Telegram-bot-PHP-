# Telegram-bot-PHP :blue_heart:

## Основные понятия

API(Application Programming Interface) - это набор способов и правил, по которым различные программы общаются между собой и обмениваются данными

Метод API - это определённое действие, которое должно выполнить приложение основываясь на полученных данных (отправить сообщение, вернуть список чатов, отправить картинку и т.д.)

Token (токен) - это уникальный ключ бота, открывающий доступ к определённым данным. 

---

### Как отправлять HTTP запросы на PHP? 

Для отправки HTTP запросов можно использовать функцию `file_get_contents`, где в качестве первого главного параметра указывается ссылка.
Данная функция отлично подходит для отправки `GET` запросов, но к сожалению с помощью функции `file_get_contents`, нельзя отправлять `POST` запросы

В данном проекте для отправки `POST` запросов я буду использовать библиотеку `Curl`.

Curl - это библиотека предназначенная для получения и передачи данных через такие протоколы, как `HTTP`, `FTP`, `HTTPS`.

---

### Виды взаимодействия с приложением через API 

Существует два вида взаимодействия с приложением через `API`.

- От клиента к серверу.
- От сервера к клиенту.

В моем случае клиентом является приложение (сайт), а в качестве сервера выступает сайт куда я отправляю запросы (telegram).

API запрос - это способ общения с программой, посредствам отправки данных от клиента к серверу.

Hooks(Хуки) - это способ общения с программой, посредствам отправки данных от сервера к клиенту. 
То есть при определённых изменениях в программе, сервер (Telegram) будет отправлять данные на указанный `URL` скрипта на моем сервере (сайте).

Например. Каждый раз когда пользователи будут писать сообщения боту, данные о сообщениях будут отправляться на указанный скрипт, где вы сможете записывать сообщения в БД или отправить ответ.

Для регистрации хука нужно выполнить 2 правила: 

- разместить скрипт на виртуальный сервер (хостинг).
- Домен на который будут отправляться запросы, должен иметь SSL сертификат и работать через HTTPS соединение. 

---

### Документация для работы с API Telegram

Все методы и параметры для запросов берем из официальной документации телеграмм

[Справочник по Bot API](https://tlgrm.ru/docs/bots/api ) 

---

### Структура URL для отправки запросов в Telegram 

Пример URL для создания запросов к боту
`https://api.telegram.org/bot{token}/{method}`

{token} — это уникальный ключ, который выдаётся при создание бота

{method} — это метод запроса по которому получаем или отправляем данные.

---

### Параметры URL для запросов

Пример отправки сообщений методом GET. Первая часть URL содержит домен `api.telegram.org`, далее прописываем строку `bot` с токеном который нам даётся при создание бота, после чего указываем метод `sendMessage` и перечисляем GET параметры.

`https://api.telegram.org/bot############:@#$@#$@#$@#$@#$@_%$#@#%^@%#!@#%$%@#/sendMessage?chat_id=<ID чата>&text=<text>`

Отправка файлов в чат выглядит аналогично, только метод `sendMessage` заменяется на `sendDocument`. И здесь не перечисляются `GET` параметры, после указания метода, так как мы отправляем данные методом `POST`.

`https://api.telegram.org/bot############:@#$@#$@#$@#$@#$@_%$#@#%^@%#!@#%$%@#/sendDocument`

Отправка изображений в чат 

`https://api.telegram.org/bot############:@#$@#$@#$@#$@#$@_%$#@#%^@%#!@#%$%@#/sendPhoto`

---

### Разбор ответа от Telegram.

При создании запроса к боту, Telegram всегда отправляет ответ, который вы можете записать в переменную и вывести на экран.
Если допустили ошибку, то придёт сообщение с параметрами, в которых указан код ошибки с описанием.
Пример получаемого ответа от Telegram:
```
{
  "ok": true,
  "result": {
    "message_id": 12,
    "from": {
      "id": #$@%%#@^$#@^%$#@,
      "is_bot": true,
      "first_name": "test_prog_time",
      "username": "test_prog_time_bot"
    },
    "chat": {
      "id": #$@%%#@^$#@^%$#@,
      "first_name": "Дмитрий",
      "last_name": "Пирогов",
      "username": "PirogovDS",
      "type": "private"
    },
    "date": 1658907913,
    "text": "Новое сообщение из формы"
  }
}
```
В ответе мы видим следующее:
1. Параметр «ok» — описывает успешность отправки запроса
2. «result» — возвращает массив с данными ответа, в которых:
    - «message_id» — id созданного сообщения
    - «from» — кто отправил сообщение
    - «chat» — данные о чате в который попало сообщение
    - «date» — дата создания сообщения
    - «text» — текст сообщения

Важно!!! Все ответы от telegram приходят в формате json, для удобства чтения данных, можно расконвертировать json, я использую функцию `json_decode` 

---

### Для отправки сообщений в групповой чат
1. Нужно добавить бота в чат и назначить его Администратором! Главное чтобы у Бота был доступ к сообщениям.
2. После добавления бота в чат, можете отправить сообщения (к примеру: `/join`).
3. Далее нам нужно получить id нашего бота. Для этого нужно перейти по следующей ссылке, где вместо символов X нужно подставить ваш токен:
 `https://api.telegram.org/botXXXXXXXXXXXXXXXXXX/getUpdates`.
4. Теперь вам необходимо отправить команду /join в чат для активации бота. После отправки команды, вам нужно обновить страницу, чтобы сделать повторный запрос.
Здесь вам нужно записать следующий фрагмент кода — id вашего бота.
Вам нужен id со знаком минус.  
Пример:
```
"my_chat_member":{
   "chat":{
   "id": -594№";%70,
    ...
   ```

---

### Отправка ответа на сообщение

Для отправки ответа на ранее созданное сообщения, вам необходимо в новом запросе на метод `sendMessage` отправить дополнительный параметр `reply_to_message_id`, передав в него `id` сообщения, которое вы хотите прикрепить.

Полный запрос будет выглядеть так...

```

$textMessage = "Новое сообщение из формы";

$getQuery = [
    "chat_id" => TG_USER_ID,
    "text" => $textMessage,
    "parse_mode" => "html",
    "reply_to_message_id" => 45
];

$ch = curl_init("https://api.telegram.org/bot". TG_TOKEN ."/sendMessage?" . http_build_query($getQuery));

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);

$resultQuery = curl_exec($ch);
curl_close($ch);

echo $resultQuery;

```

Где `TG_USER_ID` id пользователя, `TG_TOKEN`  токен телеграмм бота.

---

### Удаление сообщений из чата

Для удаления сообщений, вам нужно воспользоваться методом `deleteMessage` и знать `id` сообщения, которое вы хотите удалить.

Пример кода для удаления сообщений выглядит так:

```
$getQuery = [
    "chat_id" => TG_USER_ID,
    "message_id"  => 47,
];

$ch = curl_init("https://api.telegram.org/bot". TG_TOKEN ."/deleteMessage?" . http_build_query($getQuery));

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);

$resultQuery = curl_exec($ch);
curl_close($ch);

echo $resultQuery;
```

Где `TG_USER_ID` id пользователя, `TG_TOKEN`  токен телеграмм бота.

---

## Отправка кнопок в чат

Существует 3 вида кнопок в чате в Telegram. 

1. Кнопки которые прикреплены к сообщению (`inline_keyboard`).
2. Кнопки, которые располагаются под строкой ввода сообщения, они называются клавиатурой (`keyboard`).
3. Кнопки меню команд, которые чаще всего располагаются слева от строки ввода сообщения.

Для отправки таких кнопок, нам нужно воспользоваться методом `sendMessage` и передать ему в качестве параметра `reply_markup` — массив со свойствами клавиатуры.

Данный массив выглядит следующим образом…
```
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
```
Первое важное правило, `reply_markup` принимает `json`, поэтому для создания кнопок, вам нужно конвертировать массив в `JSON` с помощью функции `json_encode`.
В массиве с параметрами кнопок, есть особые параметры. Эти параметры, так же, указаны в [документации](https://tlgrm.ru/docs/bots/api) 

- С помощью параметра text вы можете передать текст кнопки
- параметр url указывает ссылку, если вам нужно сделать кнопку для перехода на внешний ресурс
- параметр callback_data указывает строку, которая будет возвращена после нажатия на кнопку. Данную строку используют как команду.

Массив для кнопок имеет сложную многоуровневую систему. Первый уровень отвечает за общую запись параметров, второй уровень отвечает за ряд кнопок, третий уровень отвечает за параметры кнопки.
Таким образом, для создания 2 кнопок в одном ряду, будем использовать следующий код

``` 
'reply_markup' => json_encode(array(
    'inline_keyboard' => array(
	array(
	    array(
		'text' => 'Button 1',
		'callback_data' => 'test_2',
	    ),

            array(
		'text' => 'Button 2',
		'callback_data' => 'test_2',
	    ),
	)
    ),
)),
```

Для создания 2 рядов по 2 кнопки используйте код

``` 
'reply_markup' => json_encode(array(
    'inline_keyboard' => array(
	array(
	    array(
		'text' => 'Button 1',
		'callback_data' => 'test_2',
	    ),

            array(
		'text' => 'Button 2',
		'callback_data' => 'test_2',
	    ),
	),
        array(
	    array(
		'text' => 'Button 3',
		'callback_data' => 'test_3',
	    ),

            array(
		'text' => 'Button 4',
		'callback_data' => 'test_4',
	    ),
	)
    ),
)),
```
И для создания одной кнопки в первом ряду и 2 — во втором, используйте следующий код.
``` 
'reply_markup' => json_encode(array(
    'inline_keyboard' => array(
	array(
            array(
		'text' => 'Button 2',
		'callback_data' => 'test_2',
	    ),
	),
        array(
	    array(
		'text' => 'Button 3',
		'callback_data' => 'test_3',
	    ),

            array(
		'text' => 'Button 4',
		'callback_data' => 'test_4',
	    ),
	)
    ),
)),
```

---

## Отправка клавиатуры в чат 

Аналогичные параметры имеет и массив для отправки клавиатуры в чат. Для создания клавиатуры пропишем следующий код. 

``` 
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
```

Структура массивом для кнопок та же, но только есть отличие в названиях и количестве параметров.

Ключ inline_keyboard заменяется на keyboard.

А так же для клавиатуры добавляются 2 дополнительных параметра:

- `one_time_keyboard` — скрыть клавиатуру, как только она была использована. Клавиатура по-прежнему будет доступна, но клиенты будут автоматически отображать обычную, буквенную клавиатуру в чате — пользователь может нажать специальную кнопку в поле ввода, чтобы снова увидеть пользовательскую клавиатуру. Значение по умолчанию равно false.
- `resize_keyboard` — изменяет размер клавиатуры по вертикали для оптимальной подгонки (например, уменьшить клавиатуру, если есть только два ряда кнопок). По умолчанию установлено значение false, и в этом случае пользовательская клавиатура всегда имеет ту же высоту, что и стандартная клавиатура приложения.

---

## Отправка изображений в Telegram чат

Пример отправки изображения выглядит так: используем метод `sendPhoto`

``` 
$arrayQuery = array(
    'chat_id' => TG_USER_ID,
    'caption' => 'Проверка работы',
    'photo' => curl_file_create(__DIR__ . '/1.jpg', 'image/jpg' , '1.jpg')
);
$ch = curl_init('https://api.telegram.org/bot'. TG_TOKEN .'/sendPhoto');
curl_setopt($ch, CURLOPT_POST, 1); // данный параметр говорит, что мы отправлем POST запрос, 
curl_setopt($ch, CURLOPT_POSTFIELDS, $arrayQuery); // в данный параметр передаем массив с пост данными
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);
$res = curl_exec($ch); //записываем ответ от telegram 
curl_close($ch);
```

Здесь собираем в массив `$arrayQuery` параметры для отправки запросов. Для отправки изображения, нам необходимо передать `id` чата, текст сообщения (для изображений он передается в параметре `caption`), и новый параметр `photo` в который мы передаём сформированный, с помощью функции `curl_file_create()`, объект изображения.

Ниже указываем что все данные должны передаваться методом POST и не забываем передавать токен в URL запроса.

Таким образом отправляем сжатое изображение в чат с указанной подписью.

Дополнительные параметры, которые предлагает нам документация Telegram.
- `protect_content` — данный параметр запрещает сохранение и пересылку изображения.
- `reply_markup` — позволяет добавить кнопки под изображение

---

## Отправка файлов в Telegram чат

Пример отправки файла выглядит так: используем метод `sendDocument`

``` 
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
```

Каждый отправленный файл в чат, сохраняется на серверах Телеграм. И для того чтобы получить доступ к ранее отправленному файлу и не нагружать бота запросами, вы можете просто передать `hash` строку переданную в параметре `document -> file_id` и таким образом повторить отправку файла.

Выглядит это следующим образом: 

``` 
 $arrayQuery = array(
    'chat_id' => TG_USER_ID,
    'caption' => 'Проверка работы',
    'document' => "BQACAgIAAxkDAAMUYuIyV6oVVT81C3UNccPy3mHGRkcAAhoZAAJgsBBLsPDr0DTbBw0pBA",
);		
$ch = curl_init('https://api.telegram.org/bot'. TG_TOKEN .'/sendDocument');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $arrayQuery);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);
$res = curl_exec($ch);
curl_close($ch);
```

---

## Групповая отправка изображений и файлов

Для групповой отправки изображений в чат, нам необходимо воспользоваться методом `sendMediaGroup()` и немного переделать наш массив с параметрами запроса.

Вот так будет выглядеть наш следующий пример.

``` 
$arrayQuery = [
    'chat_id' => TG_USER_ID,

    'media' => json_encode([
        ['type' => 'photo', 'media' => 'attach://photo' ],
        ['type' => 'photo', 'media' => 'attach://photo_2' ],
    ]),

    'photo' => new CURLFile(__DIR__ . '/1.jpg'),
    'photo_2' => new CURLFile(__DIR__ . '/2.jpg'),
];
$ch = curl_init('https://api.telegram.org/bot'. TG_TOKEN .'/sendMediaGroup');
curl_setopt($ch, CURLOPT_POST, 1); // данный параметр говорит, что мы отправлем POST запрос,
curl_setopt($ch, CURLOPT_POSTFIELDS, $arrayQuery); // в данный параметр передаем массив с пост данными
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);
$res = curl_exec($ch); //записываем ответ от telegram
vardump($res);
curl_close($ch);
```

Для передачи группы файлов, нам необходимо передать в качестве параметра media массив с параметрами изображений которые необходимо сгруппировать.

Каждый массив вложенный в параметр media имеет следующие параметры:

- `type` — тип файла который необходимо передать (в нашем случае это photo)
- `media` — строка указывающая вложенный файл. Добавление подстроки `attach://` является обязательным правилом. 

---

# Работа с хуками в Телеграм

## Регистрация хука для Telegram бота

Для регистрации хука нам нужно отправить запрос с методом `setWebhook()`, которому в качестве параметра `url` мы должны передать ссылку на скрипт обработчик. В моём случае это просто php скрипт.






