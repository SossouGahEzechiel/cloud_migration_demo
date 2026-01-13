<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\LevelResource;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(
	name: 'Levels',
	description: 'Gestion des niveaux'
)]
class LevelController extends Controller
{
	#[OA\Get(
		path: '/levels',
		operationId: 'getLevelsList',
		summary: 'Lister tous les niveaux',
		description: 'Retourne la liste complète de tous les niveaux',
		tags: ['Levels'],
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
							items: new OA\Items(ref: '#/components/schemas/Level')
						)
					]
				)
			)
		]
	)]
	public function index(): AnonymousResourceCollection
	{
		return LevelResource::collection(Level::all());
	}

	#[OA\Post(
		path: '/levels',
		operationId: 'storeLevel',
		summary: 'Créer un nouveau niveau',
		description: 'Crée un nouveau niveau avec les données fournies',
		tags: ['Levels'],
		requestBody: new OA\RequestBody(
			required: true,
			content: new OA\JsonContent(
				required: ['name'],
				properties: [
					new OA\Property(
						property: 'name',
						type: 'string',
						maxLength: 255,
						example: '2nde F1'
					),
					new OA\Property(
						property: 'description',
						type: 'string',
						maxLength: 255,
						example: 'Seconde F1'
					)
				]
			)
		),
		responses: [
			new OA\Response(
				response: 201,
				description: 'Niveau créé avec succès',
				content: new OA\JsonContent(
					type: 'object',
					properties: [
						new OA\Property(
							property: 'data',
							ref: '#/components/schemas/Level'
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
	public function store(Request $request): LevelResource
	{
		$request->validate([
			'name' => ['required', 'string', 'max:255'],
			'description' => ['nullable', 'string', 'max:255'],
		]);

		$level = Level::create($request->only(['name', 'description']));

		return new LevelResource($level);
	}

	#[OA\Get(
		path: '/levels/{id}',
		operationId: 'getLevelById',
		summary: 'Afficher un niveau',
		description: 'Retourne les détails d\'un niveau par son ID',
		tags: ['Levels'],
		parameters: [
			new OA\Parameter(
				name: 'id',
				in: 'path',
				required: true,
				schema: new OA\Schema(type: 'integer', example: 1)
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
							ref: '#/components/schemas/Level'
						)
					]
				)
			),
			new OA\Response(
				response: 404,
				description: 'Niveau non trouvé'
			)
		]
	)]
	public function show(string $id): LevelResource|JsonResponse
	{
		$level = Level::find($id);

		if (!$level) {
			return _404();
		}

		return new LevelResource($level);
	}

	#[OA\Put(
		path: '/levels/{id}',
		operationId: 'updateLevel',
		summary: 'Mettre à jour un niveau',
		description: 'Met à jour les données d\'un niveau existant',
		tags: ['Levels'],
		parameters: [
			new OA\Parameter(
				name: 'id',
				in: 'path',
				required: true,
				schema: new OA\Schema(type: 'integer', example: 1)
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
						example: 'Tle F2'
					),
					new OA\Property(
						property: 'description',
						type: 'string',
						maxLength: 255,
						example: 'Terminale F2'
					)
				]
			)
		),
		responses: [
			new OA\Response(
				response: 200,
				description: 'Niveau mis à jour avec succès',
				content: new OA\JsonContent(
					type: 'object',
					properties: [
						new OA\Property(
							property: 'data',
							ref: '#/components/schemas/Level'
						)
					]
				)
			),
			new OA\Response(
				response: 404,
				description: 'Niveau non trouvé'
			),
			new OA\Response(
				response: 422,
				description: 'Erreur de validation'
			)
		]
	)]
	public function update(Request $request, string $id): LevelResource|JsonResponse
	{
		$level = Level::find($id);

		if (!$level) {
			return _404();
		}

		$request->validate([
			'name' => ['required', 'string', 'max:255'],
			'description' => ['nullable', 'string', 'max:255'],
		]);

		$level->update($request->only(['name', 'description']));

		return new LevelResource($level->refresh());
	}

	#[OA\Delete(
		path: '/levels/{id}',
		operationId: 'deleteLevel',
		summary: 'Supprimer un niveau',
		description: 'Supprime un niveau existant',
		tags: ['Levels'],
		parameters: [
			new OA\Parameter(
				name: 'id',
				in: 'path',
				required: true,
				schema: new OA\Schema(type: 'integer', example: 1)
			)
		],
		responses: [
			new OA\Response(
				response: 200,
				description: 'Niveau supprimé avec succès'
			),
			new OA\Response(
				response: 404,
				description: 'Niveau non trouvé'
			)
		]
	)]
	public function destroy(Level $level): JsonResponse
	{
		if (!$level->delete()) {
			return _404();
		}

		return _200();
	}
}
