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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('rank')->default(0);
            $table->integer('today_rank')->default(0);
            $table->integer('monthly_rank')->default(0);
            $table->integer('yearly_rank')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('rank');
            $table->dropColumn('today_rank');
            $table->dropColumn('monthly_rank');
            $table->dropColumn('yearly_rank');
        });
    }
};
