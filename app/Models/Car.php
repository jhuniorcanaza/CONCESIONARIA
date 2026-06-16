<?php

namespace App\Models;

use App\Casts\BooleanToDateCast;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Car extends EloquentModel
{
    use HasFactory;

    protected $fillable = [
        'maker_id',
        'model_id',
        'year',
        'price',
        'vin',
        'mileage',
        'car_type_id',
        'fuel_type_id',
        'user_id',
        'city_id',
        'address',
        'phone',
        'description',
        'published_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // protected $guarages = ['user_id'];

    protected $with = ['primaryImage', 'maker', 'model', 'city', 'carType', 'fuelType'];

    protected $appends = ['title'];

    protected function casts(): array
    {
        return [
            // 'published_at' => 'datetime',
            'published_at' => BooleanToDateCast::class,
        ];
    }

    protected static function booted()
    {
        static::deleting(function ($car) {
            // we need to delete each image one by one to trigger the deleting event
            $car->images->each->delete();
            // don't do this because it will not trigger the deleting event
            // $car->images()->delete();
        });
    }    

    public function features(): HasOne
    {
        return $this->hasOne(CarFeature::class);
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(CarImage::class)
            ->oldestOfMany('position')
            ->withDefault(['image_path' => asset('img/car-default.jpg')]);
    }
    public function carType(): BelongsTo
    {
        return $this->belongsTo(CarType::class);
    }

    public function maker(): BelongsTo
    {
        return $this->belongsTo(Maker::class);
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(Model::class);
    }

    public function fuelType(): BelongsTo
    {
        return $this->belongsTo(FuelType::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function images(): HasMany
    {
        // return $this->hasMany(CarImage::class)->orderBy('position');
        return $this->hasMany(CarImage::class)->oldest('position');
    }

    public function favoriteUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favourite_cars');
    }    
    public function getCreatedDate() {
        return $this->created_at->format('Y-m-d');
    }

    public function getTitleAttribute(): string
    {
        return "{$this->maker->name} {$this->model->name} - {$this->year}";
    }

    public function scopeFilter($query, array $filters)
    {
        //['maker_id', 'model_id', 'state_id', 'city_id', 'car_type_id', 'fuel_type_id', 'price_from', 'price_to', 'year_from', 'year_to', 'mileage' , 'sort']

        $query->when($filters['sort'] ?? false, function ($query, $search) {

            $orders = ['price'];

            $parts = Str::of($search)->explode('_');
            
            if ($parts->count() != 2) {
                return $query;
            } 

            [$order, $direction] = $parts;

            if (in_array($order, $orders)
                && in_array($direction, ['asc', 'desc'])
            ) {
                return $query->reorder()->orderBy($order, $direction);
            }    
        });

        $query->when($filters['maker_id'] ?? false, function ($query, $search) {
            return $query->where('maker_id', (int) $search);
        });

        $query->when($filters['model_id'] ?? false, function ($query, $search) {
            return $query->where('model_id', (int) $search);
        });

        $query->when($filters['state_id'] ?? false, function ($query, $search) {
            return $query->join('cities','cities.id','=','cars.city_id')
                ->where('cities.state_id', (int) $search);
        });

        $query->when($filters['city_id'] ?? false, function ($query, $search) {
            return $query->where('city_id', (int) $search);
        });

        $query->when($filters['car_type_id'] ?? false, function ($query, $search) {
            return $query->where('car_type_id', (int) $search);
        });

        $query->when($filters['fuel_type_id'] ?? false, function ($query, $search) {
            return $query->where('fuel_type_id', (int) $search);
        });

        $query->when($filters['price_from'] ?? false, function ($query, $search) {
            return $query->where('price', '>=', (int) $search);
        });

        $query->when($filters['price_to'] ?? false, function ($query, $search) {
            return $query->where('price', '<=', (int) $search);
        });

        $query->when($filters['year_from'] ?? false, function ($query, $search) {
            return $query->where('year', '>=', (int) $search);
        }); 

        $query->when($filters['year_to'] ?? false, function ($query, $search) {
            return $query->where('year', '<=', (int) $search);
        });

        $query->when($filters['mileage'] ?? false, function ($query, $search) {
            return $query->where('mileage', '<=', (int) $search);
        });

    }

}
