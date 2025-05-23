<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('metadata', function (Blueprint $table) {
            $table->index(['book_id'], 'metadata_book_id_index');
        });
    }

    public function down(): void
    {
        Schema::table('metadata', function (Blueprint $table) {
            //
        });
    }
};
