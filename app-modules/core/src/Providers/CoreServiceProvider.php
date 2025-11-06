<?php

namespace Modules\Core\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
	public function register(): void
	{
	}

	public function boot(): void
	{
        //$this->user = User::factory()->create();
        //Auth::setUser($this->user);
	}
}
