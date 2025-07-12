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
        // Admins Table
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password_hash');
            $table->string('role')->default('admin');
            $table->integer('failed_login_attempts')->default(0);
            $table->timestamps();
        });

        // Events Table
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

        // Event Requests Table
        Schema::create('event_requests', function (Blueprint $table) {
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

            // Requester info
            $table->string('requester_email');
            $table->string('requester_phone');
            $table->string('reference_link')->nullable();
            $table->unsignedBigInteger('requested_by')->nullable();

            // Approval workflow
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->string('approval_status')->default('pending');

            $table->timestamps();

            $table->foreign('requested_by')->references('id')->on('users')->onUpdate('cascade')->nullOnDelete();
            $table->foreign('approved_by')->references('id')->on('admins')->onUpdate('cascade')->nullOnDelete();
        });

        // Saved Events Table (pivot)
        Schema::create('saved_events', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('event_id');
            $table->timestamp('saved_at')->useCurrent();

            $table->primary(['user_id', 'event_id']);
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onUpdate('cascade')->onDelete('cascade');
        });

        // User Overview Table
        Schema::create('user_overview', function (Blueprint $table) {
            $table->integer('active_users')->default(0);
            $table->integer('total_users')->default(0);
            $table->integer('totalRegister_users')->default(0);
            $table->integer('new_users')->default(0);
        });

        // Post Overview Table
        Schema::create('post_overview', function (Blueprint $table) {
            $table->integer('totalEvent_posted')->default(0);
            $table->integer('totalProposal_event')->default(0);
            $table->integer('totalAccepted_event')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_overview');
        Schema::dropIfExists('user_overview');
        Schema::dropIfExists('admin_operations');
        Schema::dropIfExists('saved_events');
        Schema::dropIfExists('event_requests');
        Schema::dropIfExists('events');
        Schema::dropIfExists('admins');
    }
};
