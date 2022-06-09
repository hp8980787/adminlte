<?php

namespace App\Utils\Admin;

use Illuminate\Support\Facades\Storage;
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
    public function upload($file, string $document = '', string $disk = 'images', bool $rename = true): string
    {


        $url = Storage::disk('images')->put($document, $file);

        return $url;
    }

    public function url()
    {

    }
}
