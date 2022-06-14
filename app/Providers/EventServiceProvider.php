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
                ['text' => '菜单', 'icon' => 'fas fa-bars', 'url' => '1'],
                [
                    'text' => '权限管理', 'icon' => 'fas fa-key', 'submenu' => [
                    ['text' => '角色', 'url' => adminRoute('roles.index'), 'icon' => 'fas fa-user-tag'],
                    ['text' => '权限', 'icon' => 'fas fa-key', 'url' => adminRoute('permissions.index')],

                ]
                ],
                [
                    'text' => '用户管理', 'icon' => 'fas fa-users', 'submenu' =>
                    [
                        ['text' => '用户', 'url' => adminRoute('users.index'), 'icon' => 'fas fa-user']
                    ]
                ],
                [
                    'text' => '商品信息', 'url' => adminRoute('products.index'),
                    'icon' => 'fab fa-product-hunt',
                    'label_color' => 'success',
                ],
                ['text' => '采购', 'icon' => 'fa fa-shopping-cart',
                    'submenu' => [
                        ['text' => '采购', 'url' => adminRoute('purchase.index'), 'icon' => 'fa fa-shopping-cart'],
                        ['text' => '供应商', 'url' => '', 'icon' => 'fas fa-truck'],
                    ]
                ],


            );
//            $event->menu->addAfter('pages', [
//                'key' => 'account_settings',
//                'header' => 'Account Settings',
//            ]);
//
//            $event->menu->addIn('account_settings', [
//                'key' => 'account_settings_notifications',
//                'text' => 'Notifications',
//                'url' => 'account/edit/notifications',
//            ]);
//
//            $event->menu->addBefore('account_settings_notifications', [
//                'key' => 'account_settings_profile',
//                'text' => 'Profile',
//                'url' => 'account/edit/profile',
//            ]);
        });
    }
}
