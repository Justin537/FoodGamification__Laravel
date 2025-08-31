<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class workout extends Model
{
    protected $table = 'workout_items';

    protected $fillable = [
        'account_id',
        'name',
        'calories',
        'date',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
