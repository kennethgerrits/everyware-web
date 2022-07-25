<?php

namespace App\Traits;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;

trait UploadsFiles
{
    public function upload(FilesystemAdapter $storage, $file, $attribute, $folder = null, $width = null, $height = null)
    {
        if ($file instanceof SymfonyUploadedFile) {
            $file = UploadedFile::createFromBase($file);
        }

        if ($file instanceof UploadedFile && !$file->isValid()) {
            logger('invalid file');
            return false;
        }

        $originalName = basename($file->getFilename());
        if ($file instanceof UploadedFile)
        {
            $originalName = $file->getClientOriginalName();
        }

        if ($width || $height) {
            $width = $width ?: $height;
            $height = $height ?: $width;
            $image = Image::make($file)->orientate()->fit($width, $height);

            $filename = $file->hashName($folder);
            $storage->put($filename, $image->encode());
        } else {
            $image = Image::make($file)->orientate();
//            $filename = $storage->putFileAs($folder, $file, $file->hashName());
            $filename = $file->hashName($folder);
            $storage->put($filename, $image->encode());
        }

        $this->{$attribute} = $filename;

        return $originalName;
    }
}