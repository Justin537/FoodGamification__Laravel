<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable; // Import the Notifiable trait
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'accounts';

    protected $fillable = [
        'email', 'password', 'name', 'gender', 'exp',
        'berat', 'tinggi', 'inventory', 'setting',
        'is_verify'
    ];
    
    protected $casts = [
        'is_verify' => 'boolean',
    ];    
    public function verify()
    {
        $this->is_verify = true;
        $this->save();
    }
}
