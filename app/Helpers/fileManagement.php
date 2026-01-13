<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (!function_exists('update_file')) {
	/**
	 * Met à jour un fichier sur le serveur
	 *
	 * @param Request $request
	 * @param string $fileKey
	 * @param string $folderName
	 * @param string $fileOldName
	 * @param string|null $fileName
	 * @param string $disk
	 * @return string
	 */
	function update_file(Request $request, string $fileKey, string $folderName, string $fileOldName, string $fileName = null, string $disk = 'public'): string
	{
		delete_file($fileOldName, $disk);
		return store_file($request, $fileKey, $folderName, $fileName, $disk);
	}
}

if (!function_exists('delete_file')) {
	/**
	 * Supprime un fichier sur le disque 'public' du serveur
	 *
	 * @param string $fileName
	 * @param string $disk
	 * @return void
	 */
	function delete_file(string $fileName, string $disk = 'public'): void
	{
		if (Storage::disk($disk)->exists($fileName)) {
			Storage::disk($disk)->delete($fileName);
		}
	}
}

if (!function_exists('store_file')) {
	/**
	 * Stocke un fichier sur le serveur
	 *
	 * @param Request $request
	 * @param string $fileKey
	 * @param string $folderName
	 * @param string|null $fileName
	 * @param string $disk
	 * @return string
	 */
	function store_file(Request $request, string $fileKey, string $folderName, string $fileName = null, string $disk = 'public'): string
	{
		$file = $request->file($fileKey);
		$fileFullName = uniqid($fileName ? Str::slug($fileName, '_') . '_' : '') . '.' . $file->getClientOriginalExtension();
		return Storage::disk($disk)->putFileAs($folderName, $file, $fileFullName);
	}
}

if (!function_exists('get_file_url')) {
	/**
	 * Retourne l'URL d'accès à un fichier sur le disque 'public'
	 *
	 * @param string|null $file_name
	 * @param string $disk
	 * @return string
	 */
	function get_file_url(?string $file_name, string $disk = 'public'): string
	{
		if ($file_name) {
			$path = str(Storage::disk($disk)->url($file_name));
			if (app()->isLocal()) {
				$path = $path->replace('http://localhost', env('APP_LOCALE_URL'));
			}
			return $path;
		}
		return '';
	}
}

if (!function_exists('move_file')) {
	/**
	 * Déplace un fichier en le renommant et retourne le chemin du nouvel emplacement
	 *
	 * @param string $oldLocation
	 * @param string $folderName
	 * @param string $fileNamePrefix
	 * @param string $disk
	 * @return string
	 */
	function move_file(string $oldLocation, string $folderName, string $fileNamePrefix = '', string $disk = 'public'): string
	{
		$extension = '.' . Str::after($oldLocation, '.');
		$tempName = Str::before($oldLocation, '.') . '-temp';
		$newLocation = $tempLocation = $tempName . $extension;

		if (Storage::disk($disk)->copy($oldLocation, $tempLocation)) {
			if (Storage::disk($disk)->move($tempLocation, $newLocation = $folderName . '/' . uniqid($fileNamePrefix) . $extension)) {
				return $newLocation;
			}
		}
		return $newLocation;
	}
}
