<?php

namespace App\Http\Controllers\V1\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class PasswordResetController extends Controller
{
	public function sendResetLinkEmail(Request $request): JsonResponse
	{
		$request->validate([
			'email' => ['required', 'email', 'exists:users,email'],
		], [
			'email.required' => 'L\'adresse email est requise.',
			'email.email' => 'L\'adresse email doit être valide.',
			'email.exists' => 'Aucun compte n\'est associé à cette adresse email.',
		]);

		try {
			// Tentative d'envoi du lien de réinitialisation
			$status = Password::sendResetLink(
				$request->only('email')
			);

			if ($status === Password::RESET_LINK_SENT) {
				return response()->json([
					'success' => true,
					'message' => 'Un lien de réinitialisation a été envoyé à votre adresse email.'
				]);
			}

			// Gestion des erreurs
			$errorMessage = match ($status) {
				Password::RESET_THROTTLED => 'Veuillez patienter avant de faire une nouvelle demande.',
				default => 'Une erreur s\'est produite lors de l\'envoi du lien.'
			};

			return response()->json([
				'success' => false,
				'message' => $errorMessage
			], 400);
		} catch (\Exception $e) {
			Log::error('Erreur lors de l\'envoi du lien de réinitialisation: ' . $e->getMessage());

			return response()->json([
				'success' => false,
				'message' => 'Une erreur interne s\'est produite.'
			], 500);
		}
	}

	public function reset(Request $request): JsonResponse
	{
		try {
			$validator = Validator::make($request->all(), [
				'token' => ['required', 'string'],
				'email' => ['required', 'email'],
				'password' => [
					'required',
					'confirmed',
				],
			], [
				'token.required' => 'Le token de réinitialisation est requis.',
				'email.required' => 'L\'adresse email est requise.',
				'email.email' => 'L\'adresse email doit être valide.',
				'password.required' => 'Le mot de passe est requis.',
				'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
			]);

			if ($validator->fails()) {
				return response()->json([
					'success' => false,
					'message' => 'Données invalides.',
					'errors' => $validator->errors()
				], 422);
			}

			// Tentative de réinitialisation du mot de passe
			$status = Password::reset(
				$request->only('email', 'password', 'password_confirmation', 'token'),
				function (User $user, string $password) {

					$user->forceFill([
						'password' => Hash::make($password),
						'remember_token' => Str::random(60),
					])->save();

					// Déclencher l'événement de réinitialisation
					event(new PasswordReset($user));
				}
			);

			if ($status === Password::PASSWORD_RESET) {
				return _200("Votre mot de passe a été réinitialisé avec succès.");
			}

			// Gestion des erreurs
			$errorMessage = match ($status) {
				Password::INVALID_TOKEN => 'Le lien de réinitialisation est invalide ou a expiré.',
				Password::INVALID_USER => 'Aucun utilisateur trouvé avec cette adresse email.',
				default => 'Une erreur s\'est produite lors de la réinitialisation.'
			};

			return _400($errorMessage);
		} catch (\Exception $e) {
			Log::error('Erreur lors de la réinitialisation du mot de passe: ' . $e->getMessage());

			return response()->json([
				'success' => false,
				'message' => 'Une erreur interne s\'est produite.'
			], 500);
		}
	}

	public function validateResetToken(Request $request): JsonResponse
	{
		try {
			$validator = Validator::make($request->all(), [
				'token' => ['required', 'string'],
				'email' => ['required', 'email'],
			]);

			if ($validator->fails()) {
				return response()->json([
					'success' => false,
					'message' => 'Token ou email invalide.'
				], 422);
			}

			// Vérifier si le token est valide
			$user = User::where('email', $request->email)->first();

			if (!$user) {
				return response()->json([
					'success' => false,
					'message' => 'Utilisateur introuvable.'
				], 404);
			}

			// Vérifier le token via le broker de réinitialisation
			$tokenRepository = Password::getRepository();

			if (!$tokenRepository->exists($user, $request->token)) {
				return response()->json([
					'success' => false,
					'message' => 'Le token est invalide ou a expiré.'
				], 400);
			}

			return response()->json([
				'success' => true,
				'message' => 'Token valide.'
			]);
		} catch (\Exception $e) {
			Log::error('Erreur lors de la validation du token: ' . $e->getMessage());

			return response()->json([
				'success' => false,
				'message' => 'Une erreur interne s\'est produite.'
			], 500);
		}
	}

	/**
	 * Valider la force d'un mot de passe (endpoint utilitaire).
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function validatePasswordStrength(Request $request): JsonResponse
	{
		try {
			$password = $request->input('password', '');

			$criteria = [
				'hasMinLength' => strlen($password) >= 8,
				'hasUpperCase' => preg_match('/[A-Z]/', $password),
				'hasLowerCase' => preg_match('/[a-z]/', $password),
				'hasNumbers' => preg_match('/\d/', $password),
				'hasSpecialChar' => preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password),
			];

			$score = array_sum($criteria);

			$strength = match (true) {
				$score >= 5 => 'Fort',
				$score >= 3 => 'Moyen',
				default => 'Faible'
			};

			$color = match ($strength) {
				'Fort' => 'green',
				'Moyen' => 'yellow',
				default => 'red'
			};

			return response()->json([
				'success' => true,
				'strength' => $strength,
				'color' => $color,
				'criteria' => $criteria,
				'score' => $score
			]);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Erreur lors de la validation.'
			], 500);
		}
	}
}
