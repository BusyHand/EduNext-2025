<?php

namespace Modules\Auth\Security;

use Carbon\Carbon;

readonly class JwtToken
{
    public function __construct(
        public string $jwtToken,
        public Carbon $expireAt,
    ) {}
}