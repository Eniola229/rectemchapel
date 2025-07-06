<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'name', 'course', 'department', 'title', 'file_url', 'category', 'year'
    ];
}
