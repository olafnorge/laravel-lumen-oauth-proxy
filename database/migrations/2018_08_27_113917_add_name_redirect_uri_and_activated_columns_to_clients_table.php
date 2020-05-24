<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNameRedirectUriAndActivatedColumnsToClientsTable extends Migration {


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('redirect_uri')->after('client_secret');
            $table->boolean('activated')->default(true)->after('redirect_uri');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'redirect_uri',
                'activated',
            ]);
        });
    }
}
