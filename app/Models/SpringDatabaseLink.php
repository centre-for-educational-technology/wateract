<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpringDatabaseLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'spring_id', 'database_name', 'code', 'spring_name', 'url'
    ];

    public function spring()
    {
        return $this->belongsTo('App\Models\Spring');
    }
}
