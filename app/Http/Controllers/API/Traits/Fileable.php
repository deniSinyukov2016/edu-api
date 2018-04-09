<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.03.18
 * Time: 15:23
 */

namespace App\Http\Controllers\API\Traits;

use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait Fileable
{
    public function addFile(UploadedFile $file)
    {
        if (!Storage::exists($this->getFileDir())) {
            Storage::makeDirectory($this->getFileDir());
        }

        $file->store($this->getFileDir());

        $data = $this->getData($file);

        $this->isSertificate($data);

        /** @var File $fileItem */
        $fileItem = $this->files()->create($data);

        return $fileItem;
    }

    public function getFileDir(): string
    {
        return '/public/files/';
    }

    public function deleteFile(array $ids)
    {
        $this->files()->whereIn('id', $ids)->delete();

        if ($this->files()->count() <= 0) {
            Storage::deleteDirectory($this->getFileDir());
        }
    }

    public function updateFile(File $file, UploadedFile $uplfile)
    {
        $data = $this->getData($uplfile);

        $this->isSertificate($data);

        $uplfile->store($this->getFileDir());

        return $this->files()->whereKey($file->id)->update($data);
    }

    /**
     * @param $data
     */
    private function isSertificate(&$data)
    {
        if (request()->exists('is_sertificate')) {
            $data['is_sertificate'] = request()->get('is_sertificate');
        }
    }

    private function getData(UploadedFile $file)
    {
        return $data = [
            'file'          => $this->getFileDir() . $file->hashName(),
            'type'          => $file->getMimeType(),
            'size'          => $file->getSize(),
            'original_name' => $file->getClientOriginalName(),
        ];
    }
}