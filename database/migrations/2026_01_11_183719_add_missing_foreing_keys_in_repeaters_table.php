<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

	public function up(): void
	{
		Schema::table('repeaters', function (Blueprint $table) {
			$table->uuid('approved_by')->nullable();
			$table->uuid('suspended_by')->nullable();

			$table->foreign('approved_by')->references('id')->on('admins');
			$table->foreign('suspended_by')->references('id')->on('admins');
		});
	}

	public function down(): void
	{
		Schema::table('repeaters', function (Blueprint $table) {
			$table->dropForeign(['approved_by']);
			$table->dropForeign(['suspended_by']);
			$table->dropColumn(['approved_by', 'suspended_by']);
		});
	}
};
