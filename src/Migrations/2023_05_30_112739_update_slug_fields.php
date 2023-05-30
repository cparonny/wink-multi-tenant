<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSlugFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wink_tags', function (Blueprint $table) {
            $table->dropUnique('wink_tags_slug_unique');
            $table->index('slug');
        });

        Schema::table('wink_pages', function (Blueprint $table) {
            $table->dropUnique('wink_pages_slug_unique');
            $table->index('slug');
        });

        Schema::table('wink_posts', function (Blueprint $table) {
            $table->dropUnique('wink_posts_slug_unique');
            $table->index('slug');
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
            $table->dropIndex('wink_tags_slug_unique');
            $table->unique('slug');
        });

        Schema::table('wink_pages', function (Blueprint $table) {
            $table->dropIndex('wink_pages_slug_unique');
            $table->unique('slug');
        });

        Schema::table('wink_posts', function (Blueprint $table) {
            $table->dropIndex('wink_posts_slug_unique');
            $table->unique('slug');
        });
    }
}
