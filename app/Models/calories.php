<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class calories extends Model
{
    protected $table = 'food_items';

    protected $fillable = [
        'id_account',
        'category',
        'name',
        'calories',
        'date',
    ];
}
