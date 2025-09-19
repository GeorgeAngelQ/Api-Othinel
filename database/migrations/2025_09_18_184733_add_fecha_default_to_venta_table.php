<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('venta', function (Blueprint $table) {
            $table->timestamp('fecha')
                  ->default(DB::raw('CURRENT_TIMESTAMP'))
                  ->change();
        });
    }

    public function down()
    {
        Schema::table('venta', function (Blueprint $table) {
            $table->timestamp('fecha')->nullable(false)->change();
        });
    }
};
