<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Auth\Models\Role;
use Modules\Auth\Models\UserCredential;
use Modules\Core\Models\Course;
use Modules\Core\Models\UserProgress;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'email',
        'username',
        'first_name',
        'last_name',
        'phone',
        'is_active',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    public function credentials(): HasOne
    {
        return $this->hasOne(UserCredential::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withTimestamps()
            ->withPivot(['created_by', 'updated_by', 'deleted_by']);
    }

    public function ownedCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'owner_id');
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'user_courses')
            ->withTimestamps()
            ->withPivot(['created_by', 'updated_by', 'deleted_by']);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }

    public function createdRoles(): HasMany
    {
        return $this->hasMany(Role::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
