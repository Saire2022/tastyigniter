<?php

namespace Admin\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdentificationOnCustomersTable extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('customers', 'identification'))
            return;

        Schema::table('customers', function (Blueprint $table) {
            $table->text('identification')->nullable()->after('last_name');
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('identification');
        });
    }
}
