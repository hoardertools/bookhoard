<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('directories', function (Blueprint $table) {
            $table->bigInteger('library_id')->nullable(true)->change();
            $table->bigInteger('directory_id')->nullable(true);
        });
    }

    public function down()
    {
        Schema::table('directories', function (Blueprint $table) {
            //
        });
    }
};
