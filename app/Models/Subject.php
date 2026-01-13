<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class Subject extends Model
{
    use HasUuids;

	public $timestamps = false;

    protected $fillable = ['description'];
}
