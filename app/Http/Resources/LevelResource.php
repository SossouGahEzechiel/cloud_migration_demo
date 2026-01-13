<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
	schema: 'Level',
	type: 'object',
	properties: [
		new OA\Property(
			property: 'id',
			type: 'integer',
			example: 1
		),
		new OA\Property(
			property: 'name',
			type: 'string',
			example: '2nde F1'
		),
		new OA\Property(
			property: 'description',
			type: 'string',
			example: 'Seconde F1',
			nullable: true
		),
	]
)]
class LevelResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id'          => $this->id,
			'name'        => $this->name,
			'description' => $this->description,
		];
	}
}
