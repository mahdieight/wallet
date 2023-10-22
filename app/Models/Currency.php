<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        "key",
        'name',
        'symbol',
        'iso_code'
    ];


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('is_active', function (Builder $builder) {
            $builder->where('is_active', true);
        });
    }

    public function getRouteKeyName()
    {
        return 'key';
    }
}
