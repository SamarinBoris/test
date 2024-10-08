<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @mixin IdeHelperHistory
 */
class History extends Model
{
    use HasFactory, UuidTrait;

    protected $table = 'histories';

    protected $casts = [
        'before' => 'json',
        'after' => 'json',
    ];
}
