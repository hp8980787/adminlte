<?php

namespace App\Utils\Admin;

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
}
