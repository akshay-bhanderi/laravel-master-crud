<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            return;
        }

        Schema::create('users', function (Blueprint $table) {
            $table->increments('user_id');
            $table->integer('user_role_id')->nullable();
            $table->text('user_request_ids')->nullable();
            $table->text('user_phone_no')->nullable();
            $table->string('user_otp')->nullable();
            $table->integer('user_city_id')->nullable();
            $table->integer('user_premium')->default(0);
            $table->integer('user_status')->default(3);
            $table->integer('user_availability')->default(0);
            $table->integer('user_registered')->nullable()->default(0);
            $table->text('user_fcm_token')->nullable();
            $table->integer('user_device_type')->default(1);
            $table->integer('user_session_id')->nullable();
            $table->text('user_name')->nullable();
            $table->string('user_firstname')->nullable();
            $table->string('user_lastname')->nullable();
            $table->integer('user_country_id')->nullable();
            $table->text('user_country_code')->nullable();
            $table->integer('user_state_id')->nullable();
            $table->text('user_email')->nullable();
            $table->text('user_password')->nullable();
            $table->integer('user_gender')->nullable()->default(1);
            $table->text('user_address')->nullable();
            $table->text('user_profile_image')->nullable();
            $table->string('user_sweet_word', 250)->nullable();
            $table->mediumText('user_sweet_words')->nullable();
            $table->text('user_blocked_by')->nullable();
            $table->text('user_last_seen')->nullable();
            $table->text('user_timezone')->nullable();
            $table->text('other_data')->nullable();
            $table->integer('is_delete')->default(0);
            $table->integer('status')->nullable()->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
