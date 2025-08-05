<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Resident
 *
 * @property int $id
 * @property int $house_id
 * @property int|null $user_id
 * @property string $name
 * @property string $phone
 * @property string|null $email
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $move_in_date
 * @property \Illuminate\Support\Carbon|null $move_out_date
 * @property string $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\House $house
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Complaint> $complaints
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Resident newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Resident newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Resident query()
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereHouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident active()
 * @method static \Database\Factories\ResidentFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Resident extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'house_id',
        'user_id',
        'name',
        'phone',
        'email',
        'address',
        'move_in_date',
        'move_out_date',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'move_in_date' => 'date',
        'move_out_date' => 'date',
    ];

    /**
     * Get the house that this resident belongs to.
     */
    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }

    /**
     * Get the user associated with this resident.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all payments for this resident.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get all complaints for this resident.
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    /**
     * Scope a query to only include active residents.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}