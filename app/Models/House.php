<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\House
 *
 * @property int $id
 * @property string $address
 * @property string $type
 * @property float $land_area
 * @property float $building_area
 * @property string $status
 * @property string|null $owner_name
 * @property string|null $owner_phone
 * @property \Illuminate\Support\Carbon|null $handover_date
 * @property float $price
 * @property int $bedrooms
 * @property int $bathrooms
 * @property string $block_unit
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Resident> $residents
 * @property-read \App\Models\Resident|null $currentResident
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Complaint> $complaints
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|House newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|House newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|House query()
 * @method static \Illuminate\Database\Eloquent\Builder|House whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|House available()
 * @method static \Illuminate\Database\Eloquent\Builder|House sold()
 * @method static \Illuminate\Database\Eloquent\Builder|House occupied()
 * @method static \Database\Factories\HouseFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class House extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'address',
        'type',
        'land_area',
        'building_area',
        'status',
        'owner_name',
        'owner_phone',
        'handover_date',
        'price',
        'bedrooms',
        'bathrooms',
        'block_unit',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'handover_date' => 'date',
        'land_area' => 'decimal:2',
        'building_area' => 'decimal:2',
        'price' => 'decimal:2',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
    ];

    /**
     * Get all residents for this house.
     */
    public function residents(): HasMany
    {
        return $this->hasMany(Resident::class);
    }

    /**
     * Get the current active resident for this house.
     */
    public function currentResident(): HasOne
    {
        return $this->hasOne(Resident::class)->where('status', 'active');
    }

    /**
     * Get all payments for this house.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get all complaints for this house.
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    /**
     * Scope a query to only include available houses.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope a query to only include sold houses.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSold($query)
    {
        return $query->where('status', 'sold');
    }

    /**
     * Scope a query to only include occupied houses.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOccupied($query)
    {
        return $query->where('status', 'occupied');
    }
}