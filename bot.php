<?php
include 'class.php';
$c = new telebot('bot5749036872:AAHA6REjB2GeWWeufSot2CCRnyFnEZmAj9A');
$r = $c->GetUpdates($c->update_id('r'));
    foreach ($r as $i => $v)
    {
        if($c->update_id('r') < $r[$i]['update_id'])
        {
            #======СОХРФНЕНИЕ ПОСЛЕДНЕГО ОБНОВЛЕНИЯ========
            $c->update_id($r[$i]['update_id']);
            #==============================================

            $chatID = $r[$i]['message']['chat']['id'];
            $name = $r[$i]['message']['from']['first_name'];
            $login = $r[$i]['message']['from']['username'];
            $userID = $r[$i]['message']['from']['id'];


            $c->SMessage($chatID, 'тестовое сообщение');
        }
    }
?>