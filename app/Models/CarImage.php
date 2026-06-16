<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CarImage extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['car_id', 'image_path', 'position'];

    protected $appends = ['size', 'full_path', 'name'];

    protected static function booted()
    {
        static::deleting(function ($image) {
            Storage::disk('public')->delete($image->image_path);
        });
    }    

    public function getSizeAttribute()
    {
        // if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
        //     $headers = get_headers($this->image_path, 1);
        //     return isset($headers['Content-Length']) ? (int) $headers['Content-Length'] : 0;
        // }

        return Storage::disk('public')->exists($this->image_path) 
            ? Storage::disk('public')->size($this->image_path) 
            : 0;
    }

    public function getFullPathAttribute()
    {
        return  Str::startsWith($this->image_path, 'http', $this->image_path) ? $this->image_path : '/storage/' . $this->image_path;
    }

    public function getNameAttribute()
    {
        return basename($this->image_path);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
