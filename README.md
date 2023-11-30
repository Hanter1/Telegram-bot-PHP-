# Telegram-bot-PHP :blue_heart:

---

## Содержание: 

1. [Основные понятия](#title1)
2. [Как отправлять HTTP запросы на PHP?](#title2)
3. [Виды взаимодействия с приложением через API](#title3)
4. [Документация для работы с API Telegram](#title4)
5. [Структура URL для отправки запросов в Telegram](#title5)
6. [Параметры URL для запросов](#title6)
7. [Разбор ответа Telegram](#title7)
8. [Для отправки сообщений в групповой чат](#title8)
9. [Отправка ответа на сообщение](#title9)
10. [Удаление сообщений из чата](#title10)
11. [Отправка кнопок в чат](#title11)
12. [Отправка клавиатуры в чат](#title12)
13. [Отправка изображений в Telegram чат](#title13)
14. [Отправка файлов в Telegram чат](#title14)
15. [Групповая отправка изображений и файлов](#title15)
16. [Регистрация хука для Telegram бота](#title16)
17. [Разбор параметров передаваемых через Hooks](#title17)
18. [Данные при нажатии на кнопку в чате](#title18)
19. [Данные при отправке изображения](#title19)
20. [Скрипт для ответа на запросы через Хук](#title20)

---

### <a id="title1">Основные понятия</a>

API(Application Programming Interface) - это набор способов и правил, по которым различные программы общаются между собой и обмениваются данными

Метод API - это определённое действие, которое должно выполнить приложение основываясь на полученных данных (отправить сообщение, вернуть список чатов, отправить картинку и т.д.)

Token (токен) - это уникальный ключ бота, открывающий доступ к определённым данным. 

---

### <a id="title2">Как отправлять HTTP запросы на PHP?</a>

Для отправки HTTP запросов можно использовать функцию `file_get_contents`, где в качестве первого главного параметра указывается ссылка.
Данная функция отлично подходит для отправки `GET` запросов, но к сожалению с помощью функции `file_get_contents`, нельзя отправлять `POST` запросы

В данном проекте для отправки `POST` запросов я буду использовать библиотеку `Curl`.

Curl - это библиотека предназначенная для получения и передачи данных через такие протоколы, как `HTTP`, `FTP`, `HTTPS`.

---

### <a id="title3">Виды взаимодействия с приложением через API</a>

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

### <a id="title4">Документация для работы с API Telegram</a>

Все методы и параметры для запросов берем из официальной документации телеграмм

[Справочник по Bot API](https://tlgrm.ru/docs/bots/api ) 

---

### <a id="title5"> Структура URL для отправки запросов в Telegram </a>

Пример URL для создания запросов к боту

`https://api.telegram.org/bot{token}/{method}`

{token} — это уникальный ключ, который выдаётся при создании бота

{method} — это метод запроса по которому получаем или отправляем данные.

---

### <a id="title6">Параметры URL для запросов</a>

Пример отправки сообщений методом GET. Первая часть URL содержит домен `api.telegram.org`, далее прописываем строку `bot` с token который нам даётся при создании бота, после чего указываем метод `sendMessage` и перечисляем GET параметры.

`https://api.telegram.org/bot############:@#$@#$@#$@#$@#$@_%$#@#%^@%#!@#%$%@#/sendMessage?chat_id=<ID чата>&text=<text>`

Отправка файлов в чат выглядит аналогично, только метод `sendMessage` заменяется на `sendDocument`. И здесь не перечисляются `GET` параметры, после указания метода, так как мы отправляем данные методом `POST`.

`https://api.telegram.org/bot############:@#$@#$@#$@#$@#$@_%$#@#%^@%#!@#%$%@#/sendDocument`

Отправка изображений в чат 

`https://api.telegram.org/bot############:@#$@#$@#$@#$@#$@_%$#@#%^@%#!@#%$%@#/sendPhoto`

---

### <a id="title7">Разбор ответа от Telegram.</a>

При создании запроса к боту, Telegram всегда отправляет ответ, который вы можете записать в переменную и вывести на экран.
Если допустили ошибку, то придёт сообщение с параметрами, в которых указан код ошибки с описанием.
Пример получаемого ответа от Telegram:
```php
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

### <a id="title8">Для отправки сообщений в групповой чат</a>
1. Нужно добавить бота в чат и назначить его Администратором! Главное чтобы у Бота был доступ к сообщениям.
2. После добавления бота в чат, можете отправить сообщения (к примеру: `/join`).
3. Далее нам нужно получить id нашего бота. Для этого нужно перейти по следующей ссылке, где вместо символов X нужно подставить ваш токен:
 `https://api.telegram.org/botXXXXXXXXXXXXXXXXXX/getUpdates`.
4. Теперь вам необходимо отправить команду /join в чат для активации бота. После отправки команды, вам нужно обновить страницу, чтобы сделать повторный запрос.
Здесь вам нужно записать следующий фрагмент кода — id вашего бота.
Вам нужен id со знаком минус.  
Пример:
```php
"my_chat_member":{
   "chat":{
   "id": -111111111,
    ...
```

---

### <a id="title9">Отправка ответа на сообщение</a>

Для отправки ответа на ранее созданное сообщения, вам необходимо в новом запросе на метод `sendMessage` отправить дополнительный параметр `reply_to_message_id`, передав в него `id` сообщения, которое вы хотите прикрепить.

Полный запрос будет выглядеть так...

```php
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

### <a id="title10">Удаление сообщений из чата</a>

Для удаления сообщений, вам нужно воспользоваться методом `deleteMessage` и знать `id` сообщения, которое вы хотите удалить.

Пример кода для удаления сообщений выглядит так:

```php
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

### <a id="title11">Отправка кнопок в чат</a>

Существует 3 вида кнопок в чате в Telegram. 

1. Кнопки которые прикреплены к сообщению (`inline_keyboard`).
2. Кнопки, которые располагаются под строкой ввода сообщения, они называются клавиатурой (`keyboard`).
3. Кнопки меню команд, которые чаще всего располагаются слева от строки ввода сообщения.

Для отправки таких кнопок, нам нужно воспользоваться методом `sendMessage` и передать ему в качестве параметра `reply_markup` — массив со свойствами клавиатуры.

Данный массив выглядит следующим образом…
```php
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

```php
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

```php
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

```php
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

### <a id="title12">Отправка клавиатуры в чат</a> 

Аналогичные параметры имеет и массив для отправки клавиатуры в чат. Для создания клавиатуры пропишем следующий код. 

```php
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

### <a id="title13">Отправка изображений в Telegram чат</a>

Пример отправки изображения выглядит так: используем метод `sendPhoto`

```php
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

### <a id="title14">Отправка файлов в Telegram чат</a>

Пример отправки файла выглядит так: используем метод `sendDocument`

```php
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

Каждый отправленный файл в чат, сохраняется на серверах Телеграм. И для того чтобы, получить доступ к ранее отправленному файлу и не нагружать бота запросами, вы можете просто передать `hash` строку переданную в параметре `document -> file_id` и таким образом повторить отправку файла.

Выглядит это следующим образом: 

```php
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

### <a id="title15">Групповая отправка изображений и файлов</a>

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

### <a id="title16">Регистрация хука для Telegram бота</a>

Для регистрации хука нам нужно отправить запрос с методом `setWebhook()`, которому в качестве параметра `url` мы должны передать ссылку на скрипт обработчик. В моём случае это просто php скрипт.

На данный момент реализовано, что на 1 бота, можно повесить только 1 Webhook!

Пример запроса:

```php
$getQuery = [
    "url" => "https://prog-time.ru/tg_script/index.php"  //наш скрипт с которым мы работаем 
];
$ch = curl_init("https://api.telegram.org/bot". TG_TOKEN ."/setWebhook?" . http_build_query($getQuery));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);

$resultQuery = curl_exec($ch);
curl_close($ch);

echo $resultQuery;
```

Если запрос прошёл успешно, то получим следующий ответ:

```php
{
  "ok": true,
  "result": true,
  "description": "Webhook was set"
}
```

Данный запрос отправляется 1 раз!

### <a id="title17">Разбор параметров передаваемых через Hooks</a>

Здесь есть небольшая проблема! Скрипты будут выполняться в рандомный момент и если мы не запишем данные, то они пропадут в пустоту. Для записи ответа вы можете использовать `Базу Данных` или просто записать массив в файл `txt`.

Для записи строки я буду использовать дополнительную, функцию `writeLogFile()`

Функция принимает 2 параметра:

- первый параметр, строка для записи. В нашем случае это JSON строка.
- второй параметр используется для очистки файла и перезаписи. Если данный параметр имеет значение false, то в файл дописывается информация.

```php
function writeLogFile($string, $clear = false){
    $log_file_name = __DIR__."/message.txt";
    if($clear == false) {
	  $now = date("Y-m-d H:i:s");
	  file_put_contents($log_file_name, $now." ".print_r($string, true)."\r\n", FILE_APPEND);
    }else {
	  file_put_contents($log_file_name, '');
      file_put_contents($log_file_name, $now." ".print_r($string, true)."\r\n", FILE_APPEND);
    }
}
```

Полный код для записи информации в файл будет выглядеть следующим образом.

```php
function writeLogFile($string, $clear = false){
    $log_file_name = __DIR__."/message.txt";
    if($clear == false) {
	  $now = date("Y-m-d H:i:s");
	  file_put_contents($log_file_name, $now." ".print_r($string, true)."\r\n", FILE_APPEND);
    }else {
	  file_put_contents($log_file_name, '');
      file_put_contents($log_file_name, $now." ".print_r($string, true)."\r\n", FILE_APPEND);
    }
}

$data = file_get_contents('php://input');
writeLogFile($data, true);
```

После отправки сообщения боту, данные были отправлены на наш скрипт и мы записали их в лог файл.

Теперь выведем полученную информацию на страницу

`echo file_get_contents(__DIR__."/message.txt");`

___

### <a id="title18">Данные при нажатии на кнопку в чате</a>

Если пользователь нажал на кнопку, то на скрипт также будет отправлен запрос с данными о пользователе и о кнопке.

Отличительной особенностью таких запросов является то что главный ключ `message` заменяется на `callback_query`, а сам массив `message` будет находиться внутри.

Получить код кнопки на которую было произведено нажатие, можно из `callback_query -> data`.

```php
{
  "update_id": 803290921,
  "callback_query": {
    "id": "6118810175780540321",
    "from": {
      "id": 1424646511,
      "is_bot": false,
      "first_name": "Имя",
      "last_name": "Фамилия",
      "username": "namesurname",
      "language_code": "ru"
    },
    "message": {
      "message_id": 113,
      "from": {
        "id": 5340791844,
        "is_bot": true,
        "first_name": "test_name,
        "username": "test_bot"
      },
      "chat": {
        "id": 1424646511,
        "first_name": "Имя",
      "last_name": "Фамилия",
        "username": "namesurname",
        "type": "private"
      },
      "date": 1659335238,
      "text": "Тестовое сообщение",
      "reply_markup": {
        "inline_keyboard": [
          [
            {
              "text": "YOUR BUTTON LABEL TEXT",
              "callback_data": "test_123"
            }
          ]
        ]
      }
    },
    "chat_instance": "1111111111111111111",
    "data": "test_123"
  }
}
```

### <a id="title19">Данные при отправке изображения</a>

Данные которые приходят при отправке изображения в чат, от пользователя.

```php
{
  "update_id": 111111111,
  "message": {
    "message_id": 11,
    "from": {
      "id": 11111111111,
      "is_bot": false,
     "first_name": "Имя",
      "last_name": "Фамилия",
      "username": "namesurname"
      "language_code": "ru"
    },
    "chat": {
      "id": 11111111111,
      "first_name": "Имя",
      "last_name": "Фамилия",
      "username": "namesurname",
      "type": "private"
    },
    "date": 11111111111,
    "photo": [
      {
        "file_id": "AgACAgIAAxkBAAMqYuPYTHnTFqNQZ3DB5B-f_MovPOMAArm9MRud5CFLxgi3BP6dpsoBAAMCAANzAAMpBA",
        "file_unique_id": "AQADub0xG53kIUt4",
        "file_size": 1111,
        "width": 90,
        "height": 90
      },
      {
        "file_id": "AgACAgIAAxkBAAMqYuPYTHnTFqNQZ3DB5B-f_MovPOMAArm9MRud5CFLxgi3BP6dpsoBAAMCAANtAAMpBA",
        "file_unique_id": "AQADub0xG53kIUty",
        "file_size": 11111,
        "width": 320,
        "height": 320
      },
      {
        "file_id": "AgACAgIAAxkBAAMqYuPYTHnTFqNQZ3DB5B-f_MovPOMAArm9MRud5CFLxgi3BP6dpsoBAAMCAAN5AAMpBA",
        "file_unique_id": "AQADub0xG53kIUt-",
        "file_size": 133230,
        "width": 880,
        "height": 880
      },
      {
        "file_id": "AgACAgIAAxkBAAMqYuPYTHnTFqNQZ3DB5B-f_MovPOMAArm9MRud5CFLxgi3BP6dpsoBAAMCAAN4AAMpBA",
        "file_unique_id": "AQADub0xG53kIUt9",
        "file_size": 138716,
        "width": 800,
        "height": 800
      }
    ]
  }
}
```

После получения данного массива мы можем сохранить отправленное изображение на своём сервере. Для этого нам нужно с помощью метода `getFile` получить полный путь к изображению, передав ему в качестве параметра `file_id`.

Полный код для сохранения будет выглядеть так:

```php
/* токен */
$token = "1111111111:XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";

/* массив с параметрами запроса */
$getQuery = array(
    "file_id" => "AgACAgIAAxkBAAMqYuPYTHnTFqNQZ3DB5B-f_MovPOMAArm9MRud5CFLxgi3BP6dpsoBAAMCAAN5AAMpBA",
);
$ch = curl_init("https://api.telegram.org/bot". $token ."/getFile?" . http_build_query($getQuery));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);

$resultQuery = curl_exec($ch);
curl_close($ch);

/* записываем ответ в формате PHP массива */
$arrDataResult = json_decode($resultQuery, true);

/* записываем URL необходимого изображения */
$fileUrl = $arrDataResult["result"]["file_path"];

/* формируем полный URL до файла */
$photoPathTG = "https://api.telegram.org/file/bot". $token ."/" . $fileUrl;

/* забираем название файла */
$arrFilePath = explode("/", $fileUrl);
$newFilerPath = __DIR__ . "/img/" . $arrFilePath[1];

/* сохраняем файл на сервер */
file_put_contents($newFilerPath , file_get_contents($photoPathTG));
```

---

### <a id="title20">Скрипт для ответа на запросы через Хук</a>

Бот будет отвечать на текстовые сообщения, отправлять изображения по запросу и сохранять изображения пользователей.

Токен бота запишем в константу `TG_TOKEN`

`define("TG_TOKEN", "1111111111:XXXXXXXX111xXxxXxxXXxk1X11XX1XXxxxXXX");`

Для удобства используйте специальные функции для отправки типовых запросов на сервер Telegram. 

Функции принимают в качестве первого аргумента массив с параметрами запроса.

##### Для отправки текстовых сообщений

```php
   function TG_sendMessage($getQuery) {
    $ch = curl_init("https://api.telegram.org/bot". TG_TOKEN ."/sendMessage?" . http_build_query($getQuery));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $res = curl_exec($ch);
    curl_close($ch);

    return $res;
}
```

##### Для отправки изображений

```php
function TG_sendPhoto($arrayQuery) {
    $ch = curl_init('https://api.telegram.org/bot'. TG_TOKEN .'/sendPhoto');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $arrayQuery);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $res = curl_exec($ch);
    curl_close($ch);

    return $res;
}
```

##### Для получения данных о файле

```php
function TG_getFile($arrayQuery) {
    $ch = curl_init("https://api.telegram.org/bot". TG_TOKEN ."/getFile?" . http_build_query($arrayQuery));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $res = curl_exec($ch);
    curl_close($ch);

    return $res;
}
```

Дополнительная функция, которая выводит список всех файлов из директории. 
Данная функция будет отдавать массив с файлами, для последующего отправления random(случайного) файла в чат.

```php
   function list_files($path) {
    if ($path[mb_strlen($path) - 1] != '/') {
	$path .= '/';
    }
 
    $files = array();
    $dh = opendir($path);
    while (false !== ($file = readdir($dh))) {
	if ($file != '.' && $file != '..' && !is_dir($path.$file) && $file[0] != '.') {
	    $files[] = $file;
	}
    }
 
    closedir($dh);
    return $files;
}
```

Ниже конструкция, которую использовали для отлова хуков. 
Бот будет сразу отвечать на команды, поэтому нам не нужно записывать информацию в лог файл.

В переменные `$textMessage` записывает текст сообщения, а в переменную `$chatId` записываем `id` чата.

```php
$data = file_get_contents('php://input');

$arrDataAnswer = json_decode($data, true);
$textMessage = mb_strtolower($arrDataAnswer["message"]["text"]);
$chatId = $arrDataAnswer["message"]["chat"]["id"];
```

Ниже мы проверяем наличие файла в сообщение. 
Если пользователь отправил файл, то мы его сохраняем в папку с картинками.

Здесь желательно прописать более сложный обработчик для проверки типа файла, но на данном этапе, 
я просто буду проверять наличие файла в сообщение.

```php
if(!empty($arrDataAnswer["message"]["photo"])) {
    $documentData = array_pop($arrDataAnswer["message"]["photo"]);
}
else if(!empty($arrDataAnswer["message"]["document"])) {
    $documentData = array_pop($arrDataAnswer["message"]["document"]);
}
```

Далее прописываем проверку на текст сообщения и в случае нужного текста отправляем ответное сообщение.

```php
if($textMessage == 'привет') {
    $textMessage_bot = "Привет! Есть фото для меня";

    $arrayQuery = array(
	'chat_id' 		=> $chatId,
	'text'			=> $textMessage_bot,
	'parse_mode'	=> "html",
    );
    TG_sendMessage($arrayQuery);
}
```

Ниже пропишем подобный код для запроса изображения. Если пользователь отправил «хочу фото», 
то мы выбираем рандомное изображение и отправляем его пользователю с помощью функции `TG_sendPhoto()`.

```php
else if($textMessage == 'хочу фото') {
    $textMessage_bot = "Вот, держи!";

    $listFile = list_files(__DIR__ . "/img/");

    $max = count($listFile) - 1;
    $randIdFile = rand(0, $max);

    $filePath = __DIR__ . "/img/" . $listFile[$randIdFile];

    $arrayQuery = array(
         'chat_id' => $chatId,
	  "photo" => new CURLFile($filePath),
	  "caption" => "Вот твоё фото!"
    );
    TG_sendPhoto($arrayQuery);

} 
```

Далее пропишем код для сохранения любых, отправленных в чат, изображений.

```php
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
```

Давайте пропишем ещё 2 условия. Первое условие будет отправлять кнопку в чат, 
а второе условие будет проверять нажатие на кнопку и отправлять дополнительное сообщение.

Запрос для отправки кнопок создаём аналогично запросу со словом «Привет». По запросу мы будем отправлять 2 кнопки с `callback_data` — `but_1` и `but_2`.

```php
if($textMessage == 'отправь кнопки') {
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
```

Теперь давайте пропишем проверку нажатия на кнопки. 
Здесь нам нужно записать в переменную `$dataBut` код нашей кнопки, чтобы по нему в дальнейшем делать проверку. 
В переменную `$textMessage` и `$chatId` мы так же записываем текст сообщения и `id` пользователя, 
только в этот раз достаём эти данные из массива с ключом `callback_query`.

Ниже проверяем код нажатой кнопки и отправляем простое текстовое сообщение в ответ.

```php
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
```