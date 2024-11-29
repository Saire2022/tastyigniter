<?php

namespace Admin\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFullNameAndIdentificationOnReservationsTable extends Migration
{
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'full_name')) {
                $table->string('full_name', 128)->nullable()->after('last_name');
            }

            if (!Schema::hasColumn('reservations', 'identification')) {
                $table->string('identification', 10)->nullable()->after('full_name');
            }
        });
    }

    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'full_name')) {
                $table->dropColumn('full_name');
            }

            if (Schema::hasColumn('reservations', 'identification')) {
                $table->dropColumn('identification');
            }
        });
    }
}
