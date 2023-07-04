<?php
/*
    Projecto: Guro
    Criado por Adriano Ernesto
    Em: 09/03/2023, 14:55
*/


namespace App\Helpers;

use Illuminate\Notifications\DatabaseNotification;

class NotificacoesParser
{
    static function parser(DatabaseNotification $notification, $urlPrefix = ""){
        $type = $notification->type;
        if (class_exists($type)) {
            return call_user_func([$type, 'parser'], $notification);
        } else {
            $d = $notification->toArray();
            return [
                "titulo"=> "Clica para saber mais",
                "texto"=>"Clica nesta notificação para saber mais!",
                "meta"=> $d,
            ];
        }
    }
}
