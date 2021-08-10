<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishes extends Model
{
    protected $fillable = [
        'name', 
        'description', 
        'price', 
        'image',
        'user_id',
        'isDone',
        'created_at',
        'updated_at'
    ];
}
