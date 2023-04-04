<?php

namespace Database\Seeders;

use App\Models\Chat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Messages extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $chats = Chat::where('active', true)
            ->take(3)
            ->get();

        if ($chats->count() >= 1) {
            DB::table('messages')->insert(['chat_id' => $chats[0]->id, 'from_client' => true, 'text' => 'Можно задать вопрос?', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);
            DB::table('messages')->insert(['chat_id' => $chats[0]->id, 'from_client' => false, 'text' => 'Да!', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);
            DB::table('messages')->insert(['chat_id' => $chats[0]->id, 'from_client' => false, 'text' => 'Так каков ваш вопрос?', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);
        }


        if ($chats->count() >= 2) {
            DB::table('messages')->insert(['chat_id' => $chats[1]->id,
                'from_client' => true, 'text' => 'Добрый день! У меня есть вопрос...',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")]);
            DB::table('messages')->insert(['chat_id' => $chats[1]->id,
                'from_client' => false,
                'text' => 'Добрый день! Чем могу помочь?',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")]);
            DB::table('messages')->insert(['chat_id' => $chats[1]->id,
                'from_client' => true,
                'text' => 'Как прикрепиться к поликлинике?',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")]);
            DB::table('messages')->insert(['chat_id' => $chats[1]->id,
                'from_client' => false,
                'text' => 'Выберите любую поликлинику из реестра ФОМС в своём регионе. Обратитесь в регистратуру с паспортом и полисом ОМС и СНИЛС. Если прикрепляете ребёнка младше 14 лет, принесите его свидетельство о рождении, полис ОМС и свой паспорт. Иностранцам и людям без гражданства дополнительно понадобится вид на жительство, беженцам — удостоверение беженца.',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")]);
        }

    }
}
