<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Auth\Models\UserCredential;
use Modules\Core\Models\Course;
use Modules\Core\Models\UserLesson;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, SoftDeletes, HasRoles;

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

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [
            'email' => $this->email,
            'username' => $this->username,
            // 'roles' => $this->getRoleNames()->toArray(),
            // 'permissions' => $this->getAllPermissions()->pluck('name')->toArray(),
        ];
    }

    public function credentials(): HasOne
    {
        return $this->hasOne(UserCredential::class);
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
        return $this->hasMany(UserLesson::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
