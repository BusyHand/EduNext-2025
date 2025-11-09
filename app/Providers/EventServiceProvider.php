<?php

namespace App\Providers;

use App\Events\AcceptedTaskAnswerEvent;
use App\Events\GenerateTaskRequestEvent;
use App\Events\PendingTaskAnswerEvent;
use App\Events\RejectedTaskAnswerEvent;
use App\Events\TaskGenerationCompletedEvent;
use Illuminate\Support\ServiceProvider;
use Modules\AiIntegration\Listener\GenerateTaskRequestListener;
use Modules\AiIntegration\Listener\PendingTaskAnswerListener;
use Modules\Core\Listeners\AcceptedTaskAnswerListener;
use Modules\Core\Listeners\RejectedTaskAnswerListener;
use Modules\Core\Listeners\TaskGenerationCompletedListener;

class EventServiceProvider extends ServiceProvider
{

    protected array $listen = [
        TaskGenerationCompletedEvent::class => [
            TaskGenerationCompletedListener::class,
        ],
        AcceptedTaskAnswerEvent::class => [
            AcceptedTaskAnswerListener::class,
        ],
        RejectedTaskAnswerEvent::class => [
            RejectedTaskAnswerListener::class,
        ],
        GenerateTaskRequestEvent::class => [
            GenerateTaskRequestListener::class,
        ],
        PendingTaskAnswerEvent::class => [
            PendingTaskAnswerListener::class,
        ],
    ];


    public function boot(): void {}
}