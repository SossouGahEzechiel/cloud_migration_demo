<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Teaching extends Model
{
    use HasUuids;

	public $timestamps = false;

    protected $fillable = [
        'title',
        'repeater_id', 
        'subject_id', 
        'level_id'
    ];
}
