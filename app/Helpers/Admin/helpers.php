<?php
if (!function_exists('adminRoute')) {
    function adminRoute($router,array $array=[]):string
    {
        return route('admin.'.$router,$array);
    }
}
