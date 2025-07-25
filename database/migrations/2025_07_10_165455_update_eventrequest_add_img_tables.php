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
        Schema::table('event_requests', function (Blueprint $table) {
            // add 'image' column to store event images
            $table->string('image')->nullable()->after('reference_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_requests', function (Blueprint $table) {
            // drop 'image' column if it exists
            if (Schema::hasColumn('event_requests', 'image')) {
                $table->dropColumn('image');
            }
        });
    }
};
