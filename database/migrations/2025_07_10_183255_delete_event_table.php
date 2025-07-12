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
        // Drop the 'saved_events' table (or drop the foreign key constraint first if you want to keep the table)
        Schema::dropIfExists('saved_events');
        Schema::dropIfExists('events');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('start_datetime');
            $table->timestamp('end_datetime')->nullable();
            $table->string('location');
            $table->string('area');
            $table->string('event_type');
            $table->boolean('is_free')->default(true);
            $table->decimal('price', 10, 2)->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->string('approval_status')->default('pending');
            $table->timestamps();

            $table->foreign('approved_by')->references('id')->on('admins')->onUpdate('cascade')->nullOnDelete();
        });
    }
};
