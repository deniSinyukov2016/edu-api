<?php

namespace App\Jobs;

use App\Models\Interfaces\IFileable;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\Dispatchable;

class FilePodcast
{
    use Dispatchable;

    protected $request;

    protected $fileUrls;

    protected $model;

    /**
     * Create a new job instance.
     *
     * @param Request $request
     * @param IFileable $model
     */
    public function __construct(Request $request, IFileable $model)
    {
        $this->request  = $request;
        $this->model    = $model;
        $this->fileUrls = [];
    }

    public function handle()
    {
        if (!$this->request->hasFile('files')) {
            return $this->fileUrls;
        }

        $files = $this->request->file('files');
        if (is_array($files)) {
            $files = collect($files);
            $files->each(function ($file) {
                $this->fileUrls[] = $this->model->addFile($file)->file;
            });

            return $this->fileUrls;
        }

        $this->fileUrls[] = $this->model->addFile($files)->file;

        return $this->fileUrls;
    }

    /**
     * @return array
     */
    public function getFileUrls(): array
    {
        return $this->fileUrls;
    }
}
