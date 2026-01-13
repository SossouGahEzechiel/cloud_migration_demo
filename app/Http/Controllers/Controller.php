<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes as OA;

#[OA\Info(
	title: 'Cloud migration Demo',
	version: '1.0.0',
	description: 'Documentation de l\'API de test de migration cloud'
)]
#[OA\Server(
	url: '/api/v1',
	description: 'API Server'
)]
#[OA\Tag(
	name: 'Levels',
	description: 'Gestion des niveaux'
)]
abstract class Controller extends BaseController {}
