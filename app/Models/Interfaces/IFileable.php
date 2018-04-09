<?php

namespace App\Models\Interfaces;

use App\Models\File;
use Illuminate\Http\UploadedFile;

interface IFileable
{
    public function addFile(UploadedFile $file);

    public function getFileDir(): string;

    public function deleteFile(array $ids);

    public function updateFile(File $fileModel, UploadedFile $file);
}