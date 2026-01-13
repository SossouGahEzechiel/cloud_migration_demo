<?php

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Il y a une forme de magie moire ici, mais souvent, ça ne tue pas
 * Si tu mets des helpers dans ton projet Laravel, il faut faire une petite config supplémentaire.
 * https://chatgpt.com/share/68892dee-3d44-8004-bed7-949132b82167 pour comprendre le dohi dont je parle
 */

if (!function_exists("_404")) {
	/**
	 * Returns a 404 JSON response with a specified message.
	 *
	 * @param string $message The message to include in the response
	 * @return JsonResponse A JSON response with a 404 status code
	 */
	function _404(string $message = 'Resource not found'): JsonResponse
	{
		return response()->json(['message' => $message], SymfonyResponse::HTTP_NOT_FOUND);
	}
}

if (!function_exists("_400")) {
	/**
	 * Returns a 404 JSON response with a specified message.
	 *
	 * @param string $message The message to include in the response
	 * @return JsonResponse A JSON response with a 404 status code
	 */
	function _400(string $message = 'Action non autorisée'): JsonResponse
	{
		return response()->json(['message' => $message], SymfonyResponse::HTTP_BAD_REQUEST);
	}
}

if (!function_exists("_500")) {
	/**
	 * Returns a 500 JSON response with a specified message.
	 *
	 * @param string $message The message to include in the response
	 * @return JsonResponse A JSON response with a 500 status code
	 */
	function _500(string $message = 'An expected error occurred on the server'): JsonResponse
	{
		return response()->json(['message' => $message], SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR);
	}
}

if (!function_exists("_200")) {
	/**
	 * Returns a 500 JSON response with a specified message.
	 *
	 * @param string $message The message to include in the response
	 * @return JsonResponse A JSON response with a 500 status code
	 */
	function _200(string $message = 'Requête exécutée avec succès'): JsonResponse
	{
		return response()->json(['message' => $message], SymfonyResponse::HTTP_OK);
	}
}
