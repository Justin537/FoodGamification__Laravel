<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XpHistory extends Model
{
    protected $table = 'xp_histories';

    protected $fillable = [
        'account_id',
        'xp_gained',
        'category',
        'date',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}

