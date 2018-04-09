<?php

namespace App\Http\Controllers\API\Traits;

use App\Exceptions\ImageException;
use App\Models\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait Imageable
{
    /**
     * @param UploadedFile $image
     *
     * @return Image
     * @throws ImageException
     */
    public function addImage(UploadedFile $image)
    {
        if ($this->images()->where('imageable_id', $this->id)->exists()) {
            throw new ImageException();
        }

        if (!Storage::exists($this->getImageDir())) {
            Storage::makeDirectory($this->getImageDir());
        }

        $image->store($this->getImageDir());
        /** @var Image $photo */
        $photo = $this->images()->create(['image' => $this->getImageDir() . $image->hashName()]);

        return $photo;
    }

    public function getImageDir(): string
    {
        return '/public/images/';
    }

    public function deleteImage()
    {
        $this->images()->delete();

        Storage::deleteDirectory($this->getImageDir());
    }

    public function imageUpdate(UploadedFile $uplfile)
    {
        $photo = $this->images()->update(['image' => $this->getImageDir() . $uplfile->hashName()]);
        $uplfile->store($this->getImageDir());

        return $photo;
    }
}
