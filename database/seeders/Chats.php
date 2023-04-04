<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Chats extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('chats')->insert(['user_id' => 45552, 'active' => false, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);
        DB::table('chats')->insert(['user_id' => 45552, 'active' => true, 'snils' => '999-113-666 73', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);    //активный диалог, СНИЛС сгенерированный
        DB::table('chats')->insert(['user_id' => 45552, 'active' => true, 'snils' => '999-113-666 73', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);    //активный диалог, СНИЛС сгенерированный
    }
}
