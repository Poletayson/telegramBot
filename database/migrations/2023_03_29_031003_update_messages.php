<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messages', function(Blueprint $table){
            $table->boolean('read')->nullable();
        });

//        create('messages', function (Blueprint $table) {
//            $table->id();
//            $table->timestamps();
//            $table->foreignId('chat_id')->constrained('chats')->onDelete('cascade');   //ID чата
//            $table->boolean('from_client')->nullable(); //Флаг того, что сообщение от клиента
//            $table->string('text', 4096)->nullable();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
