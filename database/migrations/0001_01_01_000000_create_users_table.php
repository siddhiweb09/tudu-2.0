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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code')->nullable();
            $table->string('employee_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('mobile_no_official')->nullable();
            $table->string('mobile_no_personal')->nullable();
            $table->date('doj')->nullable(); // date of joining
            $table->date('dob')->nullable(); // date of birth
            $table->string('department')->nullable();
            $table->string('job_title_designation')->nullable();
            $table->string('branch')->nullable();
            $table->string('zone')->nullable();
            $table->string('email_id_official')->nullable();
            $table->string('email_id_password')->nullable();
            $table->text('script')->nullable();
            $table->string('telegram_token')->nullable();
            $table->string('telegram_chat_id')->nullable();
            $table->string('telegram_channel_name')->nullable();
            $table->string('telegram_user_name')->nullable();
            $table->string('email_id_personal')->nullable();
            $table->string('pan_card_no')->nullable();
            $table->string('shift_details')->nullable();
            $table->string('status')->nullable();
            $table->text('profile_picture')->nullable();
            $table->timestamp('email_verified_at')->nullable(); // standard in Laravel
            $table->string('password')->nullable(); // required for auth
            $table->rememberToken(); // adds remember_token field
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
