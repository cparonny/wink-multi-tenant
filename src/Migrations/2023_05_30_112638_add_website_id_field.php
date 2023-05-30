<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWebsiteIdField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wink_tags', function (Blueprint $table) {
            $table->uuid('website_id')->nullable()->index();
        });

        Schema::table('wink_pages', function (Blueprint $table) {
            $table->uuid('website_id')->nullable()->index();
        });

        Schema::table('wink_posts', function (Blueprint $table) {
            $table->uuid('website_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wink_tags', function (Blueprint $table) {
            $table->dropColumn('website_id');
        });

        Schema::table('wink_pages', function (Blueprint $table) {
            $table->dropColumn('website_id');
        });


        Schema::table('wink_posts', function (Blueprint $table) {
            $table->dropColumn('website_id');
        });
    }
}
