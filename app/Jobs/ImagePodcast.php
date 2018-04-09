<?php

namespace App\Jobs;

use App\Models\Interfaces\IFileable;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImagePodcast
{
    use Dispatchable;

    protected $request;

    protected $fileUrls;

    protected $model;

    /**
     * Create a new job instance.
     *
     * @param Request $request
     * @param Model $model
     */
    public function __construct(Request $request, Model $model)
    {
        $this->request  = $request;
        $this->model    = $model;
        $this->fileUrls = [];
    }

    /**
     * Execute the job.
     *
     * @return array
     */
    public function handle()
    {
        if ($this->request->hasFile('image') && $this->request->file('image')->isValid()) {
            $this->fileUrls[] = $this->model->addImage($this->request->file('image'))->image;
        }

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
