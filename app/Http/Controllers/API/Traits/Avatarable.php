<?php

namespace App\Http\Controllers\API\Traits;

use App\Exceptions\ImageException;
use App\Models\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait Avatarable
{
    public function addAvatar(UploadedFile $file)
    {
        if ($this->avatar()->where('imageable_id', $this->id)->exists()) {
            throw new ImageException();
        }

        if (!Storage::exists($this->getAvatarDir())) {
            Storage::makeDirectory($this->getAvatarDir());
        }

        $file->store($this->getAvatarDir());
        /** @var Image $photo */
        $photo = $this->avatar()->create(['image' => $this->getAvatarDir() . $file->hashName()]);

        return $photo;
    }

    public function updateAvatar(UploadedFile $file)
    {
        $filePath = $this->avatar->image;

        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }

        $avatar = $this->avatar()->update(['image' => $this->getAvatarDir() . $file->hashName()]);
        $file->store($this->getAvatarDir());

        return $avatar;
    }

    public function getAvatarDir(): string
    {
        return '/public/images/';
    }
}
