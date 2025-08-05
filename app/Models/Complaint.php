<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Complaint
 *
 * @property int $id
 * @property int $house_id
 * @property int $resident_id
 * @property int|null $assigned_to
 * @property string $title
 * @property string $description
 * @property string $status
 * @property string $priority
 * @property string|null $category
 * @property array|null $attachments
 * @property string|null $response
 * @property \Illuminate\Support\Carbon|null $resolved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\House $house
 * @property-read \App\Models\Resident $resident
 * @property-read \App\Models\User|null $assignedUser
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint query()
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereHouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereResidentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint open()
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint closed()
 * @method static \Database\Factories\ComplaintFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Complaint extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'house_id',
        'resident_id',
        'assigned_to',
        'title',
        'description',
        'status',
        'priority',
        'category',
        'attachments',
        'response',
        'resolved_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'attachments' => 'array',
        'resolved_at' => 'datetime',
    ];

    /**
     * Get the house that this complaint belongs to.
     */
    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }

    /**
     * Get the resident that filed this complaint.
     */
    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    /**
     * Get the user assigned to handle this complaint.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Scope a query to only include open complaints.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['new', 'in_progress']);
    }

    /**
     * Scope a query to only include closed complaints.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeClosed($query)
    {
        return $query->whereIn('status', ['completed', 'rejected']);
    }
}