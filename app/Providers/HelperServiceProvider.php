<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
	/**
	 * Enregistre tous les fichiers helper situés dans le dossier app/Helpers.
	 *
	 * Cette méthode est appelée au moment où Laravel enregistre les services
	 * du provider, avant que l'application ne soit entièrement démarrée.
	 */
	public function register(): void
	{
		// On utilise glob() pour récupérer tous les fichiers PHP du dossier app/Helpers
		foreach (glob(app_path('Helpers/*.php')) as $helperFile) {
			// require_once inclut chaque fichier une seule fois pour éviter les conflits
			// Cela rend toutes les fonctions définies dans les fichiers helpers accessibles globalement
			require_once $helperFile;
		}
	}
}
