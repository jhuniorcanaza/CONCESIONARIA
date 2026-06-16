<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Maker extends EloquentModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    public function models()
    {
        return $this->hasMany(Model::class);
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
