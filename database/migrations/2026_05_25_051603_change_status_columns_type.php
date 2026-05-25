<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('status')->change();
        });

        Schema::table('services', function (Blueprint $table) {
            $table->string('status')->change();
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('status')->change();
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('status')->change();
        });

        Schema::table('services', function (Blueprint $table) {
            $table->boolean('status')->change();
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->boolean('status')->change();
        });
    }
};