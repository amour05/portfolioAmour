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
    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->string('title');              // Titre de l'article
        $table->string('slug')->unique();     // URL unique (ex: mon-premier-post)
        $table->text('content');              // Contenu principal
        $table->boolean('published')->default(true); // Statut de publication
        $table->timestamps();                 // created_at & updated_at
    });
}

public function down(): void
{
    Schema::dropIfExists('posts');
}


};
