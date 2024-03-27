<?php

namespace App\Services\V1;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Storage;

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

    /**
     * Update file in storage.
     */
    public function update(UploadedFile $file, string $folder, string $oldFile): string
    {
        Storage::disk('public')->delete($oldFile);

        return $this->store($file, $folder);
    }
}
