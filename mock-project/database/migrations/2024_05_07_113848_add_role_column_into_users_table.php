<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->enum('role', ['admin', 'staff'])->default('staff');
            $table->dropUnique(['phone']);
            $table->string('phone')->unique()->nullable()->default(null)->change();
            $table->string('avatar')->nullable()->default(null)->change();
            $table->text('address')->nullable()->default(null)->change();
            $table->dropColumn('is_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('role');
            $table->dropUnique(['phone']);
            $table->string('phone')->unique()->change();
            $table->string('avatar')->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->boolean('is_admin')->default(0);
        });
    }
};
