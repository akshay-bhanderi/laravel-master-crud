<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('user_roles')) {
            return;
        }

        Schema::create('user_roles', function (Blueprint $table) {
            $table->increments('role_id');
            $table->string('role_title')->nullable();
            $table->text('role_permission_ids')->nullable();
            $table->text('role_module_ids')->nullable();
            $table->longText('role_permission')->nullable();
            $table->integer('status')->default(1);
            $table->integer('is_delete')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
