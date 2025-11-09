<?php

namespace Modules\Auth\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Modules\Auth\Security\EmailPasswordUserProvider;

class AuthServiceProvider extends ServiceProvider
{
	public function register(): void
	{
	}
	
	public function boot(): void
	{
        Auth::provider('email-password', function ($app, array $config) {
            return new EmailPasswordUserProvider();
        });
	}
}
