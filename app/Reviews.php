<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    protected $fillable = [
        'app_name',
        'author',
        'body',
        'created_at',
        'previous_star_rating',
        'shop_name',
        'shop_domain',
        'star_rating',
    ];

    protected $guarded = ['updated_at'];
}
