<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создание таблицы со схемой иерархии ЧаВо
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE EXTENSION IF NOT EXISTS ltree');  //Устанавливаем расширение ltree для работы с древовидными структурами
        Schema::create('faq_shema', function (Blueprint $table) {
            $table->id();
            $table->string('title');    //Имя раздела
            $table->ltree('path'); //Путь до раздела
            $table->timestamps();
        });
    }

    /**
     * Откат миграции. Удаление таблицы со схемой иерархии ЧаВо
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faq_shema');
        DB::statement('DROP EXTENSION IF EXISTS ltree');
    }
};
