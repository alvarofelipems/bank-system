<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class Income extends Model
{
    use Uuid;

    protected $keyType = 'string';

    public $incrementing = false;
}
