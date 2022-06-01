<?php

namespace App\Utils\Admin;

use Illuminate\Support\Str;

class UploadFile
{
    protected $path;
    protected $name;

    /***
     *
     * @return bool|string
     *
     **/
    public function upload(string $path, string $name, $file, bool $rename = false)
    {
        if ($rename) {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $name = now()->format('ymdhis') . Str::random(5) . '.' . $extension;
        }
        if (!is_dir($path)) mkdir($path);
        $path = substr($path, -1, 1) === '/' ?: $path . '/';
        move_uploaded_file($file, $path . $name);
        return $path . $name;
    }

    public function url()
    {

    }
}
