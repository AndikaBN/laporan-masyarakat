<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add foreign key to users.agency_id
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('agency_id')
                ->references('id')
                ->on('agencies')
                ->nullable()
                ->cascadeOnDelete();
        });

        // Add foreign key to categories.agency_id
        Schema::table('categories', function (Blueprint $table) {
            $table->foreign('agency_id')
                ->references('id')
                ->on('agencies')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['agency_id']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['agency_id']);
        });
    }
};
