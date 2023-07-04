<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class AddUserCommand extends Command
{
    protected $signature = 'add:user';
    protected $description = 'Command description';

    public function handle()
    {
        Admin::create([
            "nome"=>"Adriano Ernesto Evaristo",
            "email"=>"angolawap@gmail.com",
            "password"=>Hash::make("123456"),
        ]);

        $this->info("Usuários criados com sucesso");

        return 1;
    }
}
