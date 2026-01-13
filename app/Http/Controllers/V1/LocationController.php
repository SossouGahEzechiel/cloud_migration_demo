<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(
	name: 'Locations',
	description: 'Gestion des emplacements'
)]
class LocationController extends Controller
{
	#[OA\Get(
		path: '/locations',
		operationId: 'getLocationsList',
		summary: 'Lister tous les emplacements',
		description: 'Retourne la liste complète de tous les emplacements',
		tags: ['Locations'],
		responses: [
			new OA\Response(
				response: 200,
				description: 'Opération réussie',
				content: new OA\JsonContent(
					type: 'object',
					properties: [
						new OA\Property(
							property: 'data',
							type: 'array',
							items: new OA\Items(ref: '#/components/schemas/Location')
						)
					]
				)
			)
		]
	)]
	public function index(): AnonymousResourceCollection
	{
		return LocationResource::collection(Location::all());
	}

	#[OA\Post(
		path: '/locations',
		operationId: 'storeLocation',
		summary: 'Créer un nouvel emplacement',
		description: 'Crée un nouvel emplacement avec les données fournies',
		tags: ['Locations'],
		requestBody: new OA\RequestBody(
			required: true,
			content: new OA\JsonContent(
				required: ['name'],
				properties: [
					new OA\Property(
						property: 'name',
						type: 'string',
						maxLength: 255,
						example: 'Adidogomé'
					)
				]
			)
		),
		responses: [
			new OA\Response(
				response: 201,
				description: 'Emplacement créé avec succès',
				content: new OA\JsonContent(
					type: 'object',
					properties: [
						new OA\Property(
							property: 'data',
							ref: '#/components/schemas/Location'
						)
					]
				)
			),
			new OA\Response(
				response: 422,
				description: 'Erreur de validation'
			)
		]
	)]
	public function store(Request $request): LocationResource
	{
		$validated = $request->validate([
			'name' => ['required', 'string', 'max:255'],
		]);

		$location = Location::create($validated);
		return new LocationResource($location);
	}

	#[OA\Get(
		path: '/locations/{id}',
		operationId: 'getLocationById',
		summary: 'Afficher un emplacement',
		description: 'Retourne les détails d\'un emplacement par son ID',
		tags: ['Locations'],
		parameters: [
			new OA\Parameter(
				name: 'id',
				in: 'path',
				required: true,
				description: 'ID de l\'emplacement',
				schema: new OA\Schema(type: 'string', format: 'uuid')
			)
		],
		responses: [
			new OA\Response(
				response: 200,
				description: 'Opération réussie',
				content: new OA\JsonContent(
					type: 'object',
					properties: [
						new OA\Property(
							property: 'data',
							ref: '#/components/schemas/Location'
						)
					]
				)
			),
			new OA\Response(
				response: 404,
				description: 'Emplacement non trouvé'
			)
		]
	)]
	public function show(string $id): LocationResource|JsonResponse
	{
		$location = Location::find($id);

		if (!$location) {
			return _404();
		}

		return new LocationResource($location);
	}

	#[OA\Put(
		path: '/locations/{id}',
		operationId: 'updateLocation',
		summary: 'Mettre à jour un emplacement',
		description: 'Met à jour les données d\'un emplacement existant',
		tags: ['Locations'],
		parameters: [
			new OA\Parameter(
				name: 'id',
				in: 'path',
				required: true,
				description: 'ID de l\'emplacement à mettre à jour',
				schema: new OA\Schema(type: 'string', format: 'uuid')
			)
		],
		requestBody: new OA\RequestBody(
			required: true,
			content: new OA\JsonContent(
				required: ['name'],
				properties: [
					new OA\Property(
						property: 'name',
						type: 'string',
						maxLength: 255,
						example: 'Agoé'
					)
				]
			)
		),
		responses: [
			new OA\Response(
				response: 200,
				description: 'Emplacement mis à jour avec succès',
				content: new OA\JsonContent(
					type: 'object',
					properties: [
						new OA\Property(
							property: 'data',
							ref: '#/components/schemas/Location'
						)
					]
				)
			),
			new OA\Response(
				response: 404,
				description: 'Emplacement non trouvé'
			),
			new OA\Response(
				response: 422,
				description: 'Erreur de validation'
			)
		]
	)]
	public function update(Request $request, string $id): LocationResource|JsonResponse
	{
		$location = Location::find($id);

		if (!$location) {
			return _404();
		}

		$validated = $request->validate([
			'name' => ['required', 'string', 'max:255']
		]);

		$location->update($validated);

		return new LocationResource($location->refresh());
	}

	#[OA\Delete(
		path: '/locations/{id}',
		operationId: 'deleteLocation',
		summary: 'Supprimer un emplacement',
		description: 'Supprime un emplacement existant',
		tags: ['Locations'],
		parameters: [
			new OA\Parameter(
				name: 'id',
				in: 'path',
				required: true,
				description: 'ID de l\'emplacement à supprimer',
				schema: new OA\Schema(type: 'string', format: 'uuid')
			)
		],
		responses: [
			new OA\Response(
				response: 200,
				description: 'Emplacement supprimé avec succès'
			),
			new OA\Response(
				response: 404,
				description: 'Emplacement non trouvé'
			)
		]
	)]
	public function destroy(string $id): JsonResponse
	{
		$location = Location::find($id);

		if (!$location) {
			return _404();
		}

		$location->delete();

		return response()->json(['message' => 'Emplacement supprimé avec succès']);
	}
}
