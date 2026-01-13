<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		// Vider la table
		DB::table('personal_access_tokens')->truncate();

		// Supprimer la colonne
		Schema::table('personal_access_tokens', function (Blueprint $table) {
			$table->dropColumn('tokenable_id');
		});

		// Recréer la colonne en UUID
		Schema::table('personal_access_tokens', function (Blueprint $table) {
			$table->uuid('tokenable_id')->after('id');
		});
	}

	public function down(): void
	{
		DB::table('personal_access_tokens')->truncate();

		Schema::table('personal_access_tokens', function (Blueprint $table) {
			$table->dropColumn('tokenable_id');
		});

		Schema::table('personal_access_tokens', function (Blueprint $table) {
			$table->unsignedBigInteger('tokenable_id')->after('id');
		});
	}
};
