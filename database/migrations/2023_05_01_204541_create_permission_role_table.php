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
    Schema::create('permission_role', function (Blueprint $table) {
        $table->bigInteger('permission_id')->unsigned();
        $table->bigInteger('role_id')->unsigned();

        $table->foreign('permission_id')->references('id')->on('permissions');
        $table->foreign('role_id')->references('id')->on('roles');

        $table->primary(['permission_id', 'role_id']);
    });
}

/**
 * Reverse the migrations.
 */
public function down(): void
{
    Schema::dropIfExists('permission_role');
}
};
