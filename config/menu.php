<?php
return [
        ['text' => '菜单', 'icon' => 'fas fa-bars', 'url' => '1'],
        [
            'text' => '权限管理', 'icon' => 'fas fa-key', 'submenu' => [
            ['text' => '角色', 'url' => url('roles.index'), 'icon' => 'fas fa-user-tag'],
            ['text' => '权限', 'icon' => 'fas fa-key', 'url' => url('permissions.index')],

        ]
        ],
        [
            'text' => '用户管理', 'icon' => 'fas fa-users', 'submenu' =>
            [
                ['text' => '用户', 'url' => url('users.index'), 'icon' => 'fas fa-user']
            ]
        ],
        [
            'text' => '商品信息', 'url' => url('products.index'),
            'icon' => 'fab fa-product-hunt',
            'label_color' => 'success',
        ],
        ['text' => '采购', 'icon' => 'fa fa-shopping-cart',
            'submenu' => [
                ['text' => '采购', 'url' => url('purchase.index'), 'icon' => 'fa fa-shopping-cart'],
                ['text' => '供应商', 'url' => '', 'icon' => 'fas fa-truck'],
            ]
        ],
];
