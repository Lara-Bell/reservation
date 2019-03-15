<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id')->comment('アポイントメントID');
            $table->string('title')->nullable()->comment('予定タイトル');
            $table->text('description')->nullable()->comment('予定詳細');
            $table->date('start_date')->nullable()->comment('開始日');
            $table->time('start_time')->nullable()->comment('開始時刻');
            $table->date('end_date')->nullable()->comment('終了日');
            $table->time('end_time')->nullable()->comment('終了時刻');
            $table->string('color')->nullable()->comment('イベントカラー');
            $table->string('text_color')->nullable()->comment('テキストカラー');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
