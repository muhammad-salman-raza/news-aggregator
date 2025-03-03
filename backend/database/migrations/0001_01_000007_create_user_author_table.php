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
        Schema::create('user_author', function (Blueprint $table) {
            $table->foreignUuid('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignUuid('author_id')
                ->constrained('authors')
                ->onDelete('cascade');
            $table->primary(['user_id', 'author_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_author');
    }
};
