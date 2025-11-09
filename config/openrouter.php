<?php

return [
    'api_endpoint' => env('OPENROUTER_API_ENDPOINT', 'https://openrouter.ai/api/v1'),
    'api_key'      => env('OPENROUTER_API_KEY'),
    'api_timeout'  => env('OPENROUTER_API_TIMEOUT', 60),
    'title'        => env('OPENROUTER_API_TITLE', 'edu-next'),
    'referer'      => env('OPENROUTER_API_REFERER', 'http://localhost:6162'),
];