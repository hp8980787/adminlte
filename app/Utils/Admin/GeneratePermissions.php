<?php

namespace App\Utils\Admin;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\PHP;
use Spatie\Permission\Models\Permission;


class GeneratePermissions
{
    public function generateRoutePermissions()
    {
        $routes = Route::getRoutes();
        $prefix = '/' . config('admin.route.prefix');
        $permissions = [];
        foreach ($routes as $k => $route) {
            if ($route->action['prefix'] == $prefix) {
                $methods = $route->methods[0];
                $action = $route->action;
                $name = $action['as'];
                $permissions[] = $name;
            }
        }
        $export = var_export($permissions, TRUE);
//        dd($export);
        $patterns = [
            "/array \(/" => '[',
            "/^([ ]*)\)(,?)$/m" => '$1]$2',
            "/=>[ ]?\n[ ]+\[/" => '=> [',
            "/([ ]*)(\'[^\']+\') => ([\[\'])/" => '$1$2 => $3',
        ];
        $export = preg_replace(array_keys($patterns), array_values($patterns), $export);
        $code = "<?php" . PHP_EOL . "return"
            . $export . ';';
        $file = fopen(config_path('routePermissions.php'), 'w+');
        fwrite($file, $code);
        fclose($file);
    }

    public function routePermissionsToDatabases()
    {
        $time = now();
        $data = array_map(function ($val) use ($time) {
            return [
                'name' => $val,
                'guard_name' => 'web',
                'created_at' => $time,
                'updated_at' => $time,
            ];
        }, config('routePermissions'));
        Permission::query()->insert($data);
    }

    public function generateMenuToConfig()
    {
        Config::set('app.locale','en');
        $menus = [
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
            ],
            [
                'text' => trans('menu.dashboard'),
                'url' => adminRoute('dashboard'),
                'icon' => 'far fa-fw fa-file',
                'label_color' => 'success',
            ],
            [
                'text' => 'pages',
                'url' => 'admin/pages',
                'icon' => 'far fa-fw fa-file',
                'label' => 4,
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
            ['text' => trans('menu.menu'), 'icon' => 'fas fa-bars', 'url' => '1'],
            [
                'text' => trans('menu.permissions'), 'icon' => 'fas fa-key', 'submenu' => [
                ['text' => trans('menu.role'), 'url' => adminRoute('roles.index'), 'icon' => 'fas fa-user-tag'],
                ['text' => trans('menu.permission'), 'icon' => 'fas fa-key', 'url' => adminRoute('permissions.index')],
            ]
            ],
            [
                'text' => trans('menu.users-manage'), 'icon' => 'fas fa-users', 'submenu' =>
                [
                    ['text' => trans('menu.users'), 'url' => adminRoute('users.index'), 'icon' => 'fas fa-user']
                ]
            ],
            [
                'text' => trans('menu.goods-information'), 'url' => adminRoute('products.index'),
                'icon' => 'fab fa-product-hunt',
                'label_color' => 'success',
            ],
            ['text' => trans('menu.purchase'), 'icon' => 'fa fa-shopping-cart',
                'submenu' => [
                    ['text' => trans('menu.purchase'), 'url' => adminRoute('purchase.index'), 'icon' => 'fa fa-shopping-cart'],
                    ['text' => trans('menu.supplier'), 'url' => '', 'icon' => 'fas fa-truck'],
                ]
            ],];
        $export = $this->var_export54($menus);
        $file = fopen(config_path('menu.php'), 'w+');
        $output = '<?php' . PHP_EOL . 'return' . PHP_EOL . $export . ';';
        fwrite($file, $output);
        fclose($file);
    }

    public function var_export54($var, $indent = "")
    {

        switch (gettype($var)) {
            case "string":
                return '"' . addcslashes($var, "\\\$\"\r\n\t\v\f") . '"';
            case "array":
                $indexed = array_keys($var) === range(0, count($var) - 1);
                $r = [];
                foreach ($var as $key => $value) {
                    $r[] = "$indent    "
                        . ($indexed ? "" : $this->var_export54($key) . " => ")
                        . $this->var_export54($value, "$indent    ");
                }
                return "[\n" . implode(",\n", $r) . "\n" . $indent . "]";
            case "boolean":
                return $var ? "TRUE" : "FALSE";
            default:
                return var_export($var, TRUE);
        }
    }

}
