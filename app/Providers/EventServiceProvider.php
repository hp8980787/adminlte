<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
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

            $menu = [
                [
                    'type' => 'navbar-search',
                    'text' => 'search',
                    'topnav_right' => true,
                ],
                [
                    'type' => 'fullscreen-widget',
                    'topnav_right' => true,
                ],
                // Sidebar items:
                [
                    'type' => 'sidebar-menu-search',
                    'text' => 'search',
                ], [
                    'text' => trans('menu.dashboard'),
                    'url' => adminRoute('dashboard'),
                    'icon' => "fal fa-tachometer",
                    'label_color' => 'success',
                ], [
                    'text' => '通知',
                    'url' => adminRoute('notifications.index'),
                    'icon' => "fa fa-bell",
                    'icon_color' => 'success',
                    'label_color' => 'success',
                    'id' => 'notifications',
                    'label' => 5
                ],

                [
                    'text' => 'pages',
                    'url' => 'admin/pages',
                    'icon' => 'far fa-fw fa-file',
                    'label' => 0,
                    'label_color' => 'success',
                ],
                ['header' => 'account_settings'],
                [
                    'text' => 'profile',
                    'url' => 'admin/settings',
                    'icon' => 'fas fa-fw fa-user',
                ],
                [
                    'text' => 'change_password',
                    'url' => 'admin/settings',
                    'icon' => 'fas fa-fw fa-lock',
                ],
                'MAIN NAVIGATION',
                ['text' => trans('menu.menu'), 'icon' => 'fas fa-bars', 'url' => '1', 'can' => 'menu.menu'],
                [
                    'text' => trans('menu.permissions'), 'icon' => 'fas fa-key', 'can' => 'menu.permissions', 'submenu' => [
                    ['text' => trans('menu.role'), 'url' => adminRoute('roles.index'), 'icon' => 'fas fa-user-tag'],
                    ['text' => trans('menu.permission'), 'icon' => 'fas fa-key', 'url' => adminRoute('permissions.index')],
                ]
                ],
                [
                    'text' => trans('menu.users-manage'), 'icon' => 'fas fa-users', 'can' => 'menu.users',
                    'submenu' => [
                        ['text' => trans('menu.users'), 'url' => adminRoute('users.index'), 'icon' => 'fas fa-user']
                    ]
                ],
                [
                    'text' => trans('menu.goods-information'), 'url' => adminRoute('products.index'),
                    'can' => 'menu.products',
                    'icon' => 'fab fa-product-hunt',
                    'label_color' => 'success',
                ],
                ['text' => trans('menu.purchase'), 'icon' => 'fa fa-shopping-cart', 'can' => 'menu.purchase',
                    'submenu' => [
                        ['text' => trans('menu.purchase'), 'url' => adminRoute('purchase.index'), 'icon' => 'fa fa-shopping-cart', 'active' => ['regex:@^.*purchase.*@'],],
                        ['text' => trans('menu.supplier'), 'url' => adminRoute('supplier.index'), 'icon' => 'fas fa-truck', 'active' => ['regex:@^.*supplier.*@']],
                        ['text' => trans('menu.storehouse'), 'url' => adminRoute('storehouse.index'), 'icon' => 'fas fa-warehouse'],
                    ]
                ],];
            $event->menu->add(...$menu);
//            $event->menu->add(
//                ['text' => '菜单', 'icon' => 'fas fa-bars', 'url' => '1'],
//                [
//                    'text' => '权限管理', 'icon' => 'fas fa-key', 'submenu' => [
//                    ['text' => '角色', 'url' => adminRoute('roles.index'), 'icon' => 'fas fa-user-tag'],
//                    ['text' => '权限', 'icon' => 'fas fa-key', 'url' => adminRoute('permissions.index')],
//
//                ]
//                ],
//                [
//                    'text' => '用户管理', 'icon' => 'fas fa-users', 'submenu' =>
//                    [
//                        ['text' => '用户', 'url' => adminRoute('users.index'), 'icon' => 'fas fa-user']
//                    ]
//                ],
//                [
//                    'text' => '商品信息', 'url' => adminRoute('products.index'),
//                    'icon' => 'fab fa-product-hunt',
//                    'label_color' => 'success',
//                ],
//                ['text' => '采购', 'icon' => 'fa fa-shopping-cart',
//                    'submenu' => [
//                        ['text' => '采购', 'url' => adminRoute('purchase.index'), 'icon' => 'fa fa-shopping-cart'],
//                        ['text' => '供应商', 'url' => '', 'icon' => 'fas fa-truck'],
//                    ]
//                ],
//            );
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
