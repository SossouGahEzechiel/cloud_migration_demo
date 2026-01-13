<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
	schema: 'Location',
	type: 'object',
	properties: [
		new OA\Property(
			property: 'id',
			type: 'string',
			format: 'uuid',
			example: '550e8400-e29b-41d4-a716-446655440000'
		),
		new OA\Property(
			property: 'name',
			type: 'string',
			example: 'Baguida'
		)
	]
)]
class LocationResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
		];
	}
}
