<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Model extends EloquentModel
{
    use HasFactory;

    public $timestamps = false;
    
    // protected $table = 'models';

    protected $fillable = ['name', 'maker_id'];

    public function maker()
    {
        return $this->belongsTo(Maker::class);
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
