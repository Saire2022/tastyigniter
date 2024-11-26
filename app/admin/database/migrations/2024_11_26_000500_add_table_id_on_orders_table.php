<?php

namespace Admin\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableIdOnOrdersTable extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('orders', 'table_id'))
            return;

        Schema::table('orders', function (Blueprint $table) {
            $table->text('table_id')->nullable()->after('last_name');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('table_id');
        });
    }
}
