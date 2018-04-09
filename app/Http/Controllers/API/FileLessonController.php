<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\AddFileRequest;
use App\Jobs\FilePodcast;
use App\Models\File;
use App\Models\Lesson;
use App\Http\Controllers\Controller;
use Storage;

class FileLessonController extends Controller
{
    /**
     * @apiDesc     Show files to lesson
     * @apiParam    integer $course in_path required| Course id
     * @apiResp     200 | Whatever message is send from backend on success
     * @apiErr      401 | Unauthorized .
     *
     * @param Lesson $lesson
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Lesson $lesson)
    {
        return response()->json($lesson->files()->paginate(request('count', 10)));
    }

    /**
     * @apiDesc     Upload files to lesson
     * @apiParam    array $files in_query required| Upload files []
     * @apiParam    integer $is_sertificate in_query | is_sertificate 1 or 0
     *
     * @apiResp     200 | Whatever message is send from backend on success
     * @apiErr      401 | Unauthorized .
     * @apiErr      422 | Validation failed .
     *
     * @param       AddFileRequest $request
     *
     * @param  Lesson $lesson
     *
     * @return \Illuminate\Http\JsonRespons
     */
    public function store(AddFileRequest $request, Lesson $lesson)
    {
        dispatch($filePodcast = new FilePodcast($request, $lesson));

        return response()->json($filePodcast->getFileUrls());
    }

    /**
     * @apiDesc     Delete files for lesson
     * @apiParam    integer $category in_path | Category id
     * @apiParam    array $file_ids in_query | Array ids []
     * @apiResp     204 | Whatever message is send from backend on success
     * @apiErr      401 | Unauthorized
     * @apiErr      404 | Not found
     *
     * @param Lesson $lesson
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Lesson $lesson)
    {
        $this->validate(request(), ['file_ids' => 'required|array']);

        return response()->json($lesson->deleteFile(request()->file_ids), 204);
    }

    /**
     * @apiDesc     Download files for lesson
     * @apiParam    integer $lesson in_path | Lesson id
     * @apiParam    integer $file in_path | File id
     * @apiResp     204 | Whatever message is send from backend on success
     * @apiErr      401 | Unauthorized
     * @apiErr      404 | Not found
     *
     * @param Lesson $lesson
     * @param File $file
     *
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(Lesson $lesson, File $file)
    {
        if ($lesson->files()->whereKey($file->id)->exists()) {
            $path = storage_path('app' . $lesson->files()->whereKey($file->id)->first()->file);

            return response()->download($path);
        }

        return response()->json([], 204);
    }

    /**
     * @apiDesc Update file for lesson
     * @apiParam integer $course in_path | Course id
     * @apiParam file $files in_query required|  File
     * @apiResp 204 | Whatever message is send from backend on success
     * @apiErr  401 | Unauthorized
     * @apiErr  404 | Not found
     *
     * @param Lesson $lesson
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Lesson $lesson)
    {
        $this->validate(request(), [
            'files'          => 'required|array',
            'files.*'        => 'required|file',
            'is_sertificate' => 'boolean'
        ]);
        $files = $lesson->files()->whereIn('id', array_keys(request('files')))->get();

        $files->each(function (File $file) {
            if (Storage::exists($file->file)) {
                Storage::delete($file->file);
            }
        });
        foreach (request('files') as $id => $fileRequest) {
            $file = File::query()->whereKey($id)->first();
            $lesson->updateFile($file, $fileRequest);
        }

        return response()->json($lesson->fresh('files'));
    }
}
