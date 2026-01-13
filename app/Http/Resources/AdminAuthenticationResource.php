<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
	schema: 'AdminAuthentication',
	type: 'object',
	properties: [
		new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '550e8400-e29b-41d4-a716-446655440000'),
		new OA\Property(property: 'lastName', type: 'string', example: 'Doe'),
		new OA\Property(property: 'firstName', type: 'string', example: 'John'),
		new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@example.com'),
		new OA\Property(property: 'createdAt', type: 'string', format: 'date-time', example: '2026-01-11T12:00:00Z'),
		new OA\Property(property: 'token', type: 'string', example: '1|abcdef123456'),
		new OA\Property(property: 'hasConfirmedPassword', type: 'boolean', example: true)
	]
)]
class AdminAuthenticationResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->resource->id,
			'lastName' => $this->resource->last_name,
			'firstName' => $this->resource->first_name,
			'email' => $this->resource->email,
			'createdAt' => $this->resource->created_at,
			'token' => $this->resource->token,
			'hasConfirmedPassword' => $this->resource->has_change_password
		];
	}
}
