<?php

namespace Modules\Core\Models;

use App\Models\User;
use App\Traits\HasUserActions;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Policies\CoursePolicy;
use Modules\Core\Policies\LessonPolicy;

#[UsePolicy(LessonPolicy::class)]
class Lesson extends Model
{
    use HasFactory, SoftDeletes, HasUserActions;

    protected $fillable = [
        'title',
        'content',
        'is_published',
        'published_at',
        'course_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
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
        static::creating(function (Lesson $lesson) {
            $lesson->published_at = $lesson->is_published ? now() : null;
        });

        static::updating(function (Lesson $lesson) {
            if ($lesson->isDirty('is_published')) {
                $lesson->published_at = $lesson->is_published ? now() : null;
            }
        });
    }
}
