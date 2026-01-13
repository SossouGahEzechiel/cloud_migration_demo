<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

	public function up(): void
	{
		Schema::create('teachings', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->uuid('repeater_id');
			$table->uuid('subject_id');
			$table->uuid('level_id');
			$table->string('title')->nullable();
			$table->foreign('repeater_id')->references('id')->on('repeaters')->onDelete('cascade');
			$table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
			$table->foreign('level_id')->references('id')->on('levels')->onDelete('cascade');
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('teachings');
	}
};
