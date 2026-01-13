<?php

namespace App\Http\Controllers\V1\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminAuthenticationResource;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

#[OA\Tag(
	name: 'Admin - Authentification',
	description: 'Gestion de l\'authentification des administrateurs'
)]
class AuthController extends Controller
{
	#[OA\Post(
		path: '/admin/auth/login',
		operationId: 'adminLogin',
		summary: 'Connexion administrateur',
		description: 'Authentifie un administrateur et retourne un token d\'accès',
		tags: ['Admin - Authentification'],
		requestBody: new OA\RequestBody(
			required: true,
			content: new OA\JsonContent(
				required: ['email', 'password'],
				properties: [
					new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@example.com'),
					new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password')
				]
			)
		),
		responses: [
			new OA\Response(
				response: 200,
				description: 'Connexion réussie',
				content: new OA\JsonContent(ref: '#/components/schemas/AdminAuthentication')
			),
			new OA\Response(
				response: 422,
				description: 'Erreur de validation',
				content: new OA\JsonContent(
					properties: [
						new OA\Property(property: 'message', type: 'string', example: 'Identifiants de connexion invalides')
					]
				)
			)
		]
	)]
	public function login(Request $request): JsonResponse|AdminAuthenticationResource
	{
		if (!$user = Admin::query()->firstWhere('email', $request->input('email'))) {
			return response()->json(['message' => "Identifiants de connexion invalides"], 422);
		}

		if (!Hash::check($request->input('password'), $user->password)) {
			return response()->json(['message' => "Identifiants de connexion invalides"], 422);
		}

		return new AdminAuthenticationResource($this->generateToken($user));
	}

	#[OA\Post(
		path: '/admin/auth/confirm-password',
		operationId: 'adminConfirmPassword',
		summary: 'Confirmer le mot de passe',
		description: 'Permet à un administrateur de confirmer son mot de passe',
		security: [['bearerAuth' => []]],
		tags: ['Admin - Authentification'],
		requestBody: new OA\RequestBody(
			required: true,
			content: new OA\JsonContent(
				required: ['password'],
				properties: [
					new OA\Property(
						property: 'password',
						type: 'string',
						format: 'password',
						minLength: 8,
						example: 'NouveauMotDePasse123!'
					),
					new OA\Property(
						property: 'password_confirmation',
						type: 'string',
						format: 'password',
						example: 'NouveauMotDePasse123!'
					)
				]
			)
		),
		responses: [
			new OA\Response(
				response: 200,
				description: 'Mot de passe confirmé avec succès',
				content: new OA\JsonContent(
					properties: [
						new OA\Property(property: 'success', type: 'boolean', example: true)
					]
				)
			),
			new OA\Response(
				response: 401,
				description: 'Non authentifié',
				content: new OA\JsonContent(
					properties: [
						new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated')
					]
				)
			),
			new OA\Response(
				response: 422,
				description: 'Erreur de validation',
				content: new OA\JsonContent(
					properties: [
						new OA\Property(property: 'message', type: 'string'),
						new OA\Property(
							property: 'errors',
							type: 'object'
						)
					]
				)
			)
		]
	)]
	public function confirmPassword(Request $request): JsonResponse
	{
		$request->validate([
			'password' => ['required', 'min:8', 'confirmed'],
		], [
			'password.required' => 'Le mot de passe est obligatoire',
			'password.min' => 'Le mot de passe doit faire au moins 8 caractères',
			'password.confirmed' => "La confirmation de mot de passe n'est pas conforme"
		]);

		$request->user()->update([
			'password' => $request->input('password'),
			'has_change_password' => true
		]);

		return response()->json(['success' => true]);
	}

	#[OA\Post(
		path: '/admin/auth/logout',
		operationId: 'adminLogout',
		summary: 'Déconnexion administrateur',
		description: 'Déconnecte l\'administrateur actuellement authentifié',
		security: [['bearerAuth' => []]],
		tags: ['Admin - Authentification'],
		responses: [
			new OA\Response(
				response: 200,
				description: 'Déconnexion réussie',
				content: new OA\JsonContent(
					properties: [
						new OA\Property(property: 'success', type: 'boolean', example: true)
					]
				)
			),
			new OA\Response(
				response: 401,
				description: 'Non authentifié',
				content: new OA\JsonContent(
					properties: [
						new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated')
					]
				)
			)
		]
	)]
	public function logout(): JsonResponse
	{
		Auth::logout();
		return response()->json(['success' => true]);
	}

	private function generateToken(Admin $user): Admin
	{
		$user->token = $user->createToken('auth')->plainTextToken;
		return $user;
	}
}
