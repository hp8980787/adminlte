<?php
if (!function_exists('adminRoute')) {
    function adminRoute($router):string
    {
        return route('admin.'.$router);
    }
}
