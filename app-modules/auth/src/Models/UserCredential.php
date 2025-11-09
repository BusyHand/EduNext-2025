<?php

namespace Modules\Auth\Models;

use App\Models\User;
use App\Traits\HasUserActions;
use Illuminate\Database\Eloquent\Model;

class UserCredential extends Model
{

    protected $fillable = [
        'user_id',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
