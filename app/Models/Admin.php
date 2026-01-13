<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
	use HasUuids, Notifiable, HasApiTokens;

	protected $fillable = [
		'email',
		'password',
		'email_verified_at',
		'first_name',
		'last_name',
		'phone',
	];
}
