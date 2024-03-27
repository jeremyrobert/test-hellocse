<?php

namespace App\Services\V1;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Store file in storage.
     */
    public function store(UploadedFile $file, string $folder): string
    {
        $name = Str::uuid().'.'.$file->extension();

        $file->storePubliclyAs('public/'.$folder, $name);

        return $name;
    }
}
