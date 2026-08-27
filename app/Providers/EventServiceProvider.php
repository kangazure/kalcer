<?php

namespace App\Providers;

use App\Events\NewContactSubmitted;
use App\Events\NewJobApplicationSubmitted;
use App\Events\NewTroubleReportSubmitted;
use App\Events\NetworkNodeStatusChanged;
use App\Events\PostPublished;
use App\Listeners\LogUserActivity;
use App\Listeners\NotifyAdminNewApplication;
use App\Listeners\NotifyAdminNewContact;
use App\Listeners\NotifyOperatorNetworkChange;
use App\Listeners\NotifyOperatorTroubleReport;
use App\Listeners\PingSearchEngineOnPublish;
use App\Listeners\SendWhatsAppNotification;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Login::class => [
            LogUserActivity::class,
        ],

        NewContactSubmitted::class => [
            NotifyAdminNewContact::class,
            SendWhatsAppNotification::class,
        ],

        NewJobApplicationSubmitted::class => [
            NotifyAdminNewApplication::class,
        ],

        NewTroubleReportSubmitted::class => [
            NotifyOperatorTroubleReport::class,
        ],

        NetworkNodeStatusChanged::class => [
            NotifyOperatorNetworkChange::class,
        ],

        PostPublished::class => [
            PingSearchEngineOnPublish::class,
        ],
    ];

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
