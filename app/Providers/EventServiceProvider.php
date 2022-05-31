<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
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
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
            $event->menu->add(
                [ 'text' => '商品信息', 'url' => route('admin.products.index'), ]
            );
            $event->menu->addAfter('pages', [
                'key' => 'account_settings',
                'header' => 'Account Settings',
            ]);

            $event->menu->addIn('account_settings', [
                'key' => 'account_settings_notifications',
                'text' => 'Notifications',
                'url' => 'account/edit/notifications',
            ]);

            $event->menu->addBefore('account_settings_notifications', [
                'key' => 'account_settings_profile',
                'text' => 'Profile',
                'url' => 'account/edit/profile',
            ]);
        });
    }
}
