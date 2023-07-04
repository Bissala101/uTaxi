<?php
/*
    Projecto: Guro
    Criado por Adriano Ernesto
    Em: 10/03/2023, 18:35
*/


namespace App\Traits;

use App\Helpers\NotificacoesParser;
use Illuminate\Notifications\Messages\MailMessage;

trait NotificationSupport
{
    public function toMail($notifiable)
    {

        $notification = $notifiable->notifications()->where("type", self::class)->where("notifiable_type", get_class($notifiable))
            ->where("notifiable_id", $notifiable->id)->orderBy("created_at", "desc")->first();

        $parsed = NotificacoesParser::parser($notification, config("app.cliente_url"));

        //dd($parsed);
        return (new MailMessage)
            ->subject($parsed["titulo"])
            ->greeting("Olá ".$notifiable->nome)
            ->markdown("mail.custom_notificacao", ["parsed" => $parsed]);
    }
}
