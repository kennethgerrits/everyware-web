<?php

namespace App\Providers;

use App\Models\Template;
use App\Models\User;
use App\Models\Wordlist;
use App\Observers\TemplateObserver;
use App\Observers\UserObserver;
use App\Observers\WordlistObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Template::observe(TemplateObserver::class);
        Wordlist::observe(WordlistObserver::class);
        User::observe(UserObserver::class);
    }
}
