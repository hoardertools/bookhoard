<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('libraries', function (Blueprint $table) {
            $table->string('type', 30);
        });
    }

    public function down()
    {
        Schema::table('libraries', function (Blueprint $table) {
            //
        });
    }
};
