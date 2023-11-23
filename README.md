# Telegram-bot-PHP :blue_heart:

##Основные понятия
API(Application Programming Interface) - это набор способов и правил, по которым различные программы общаются между собой и обмениваются данными

Метод API - это определённое действие, которое должно выполнить приложение основываясь на полученных данных( отправить сообщение, вернуть список чатов, отправить картинку и т.д.)

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
