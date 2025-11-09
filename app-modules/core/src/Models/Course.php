<?php

namespace Modules\Core\Models;

use App\Models\User;
use App\Traits\HasUserActions;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Policies\CoursePolicy;

/** @mixin Eloquent */
#[UsePolicy(CoursePolicy::class)]
class Course extends Model
{
    use HasFactory, SoftDeletes, HasUserActions;

    protected $fillable = [
        'title',
        'description',
        'is_published',
        'published_at',
        'owner_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_courses')
            ->withTimestamps()
            ->withPivot(['created_by', 'updated_by', 'deleted_by']);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function progress()
    {
        return $this->hasMany(UserLesson::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    protected static function booted(): void
    {
        static::creating(function (Course $course) {
            $course->published_at = $course->is_published ? now() : null;
        });

        static::updating(function (Course $course) {
            if ($course->isDirty('is_published')) {
                $course->published_at = $course->is_published ? now() : null;
            }
        });
    }
}
