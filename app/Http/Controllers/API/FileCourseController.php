<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\AddFileRequest;
use App\Http\Controllers\Controller;
use App\Jobs\FilePodcast;
use App\Models\Course;
use App\Models\File;
use Storage;

class FileCourseController extends Controller
{
    /**
     * @apiDesc Show files to course
     * @apiParam integer $course in_path required| Course id
     * @apiResp 200 | Whatever message is send from backend on success
     * @apiErr  401 | Unauthorized .
     *
     * @param Course $course
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \InvalidArgumentException
     */
    public function show(Course $course)
    {
        return response()->json($course->files()->paginate(request('count', 10)));
    }

    /**
     * @apiDesc Upload files to course
     * @apiParam array $files in_query required| Upload files []
     * @apiParam integer is_sertificate in_query | is_sertificate 1 or 0. default 0
     *
     * @apiResp 200 | Whatever message is send from backend on success
     * @apiErr  401 | Unauthorized .
     * @apiErr  422 | Validation failed .
     *
     * @param AddFileRequest $request
     * @param Course $course
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AddFileRequest $request, Course $course)
    {
        dispatch($filePodcast = new FilePodcast($request, $course));

        return response()->json($filePodcast->getFileUrls());
    }

    /**
     * @apiDesc Delete files for course
     * @apiParam integer $category in_path | Category id
     * @apiParam array $file_ids in_query | Array ids []
     * @apiResp 204 | Whatever message is send from backend on success
     * @apiErr  401 | Unauthorized
     * @apiErr  404 | Not found
     *
     * @param Course $course
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Course $course)
    {
        $this->validate(request(), ['file_ids' => 'required|array']);

        return response()->json($course->deleteFile(request('file_ids', [])), 204);
    }

    /**
     * @apiDesc Update file for course
     * @apiParam integer $course in_path | Course id
     * @apiParam integer $course in_path | File id
     * @apiParam array $file_ids in_query | Array ids []
     * @apiResp 204 | Whatever message is send from backend on success
     * @apiErr  401 | Unauthorized
     * @apiErr  404 | Not found
     *
     * @param Course $course
     * @param File $file
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Course $course, File $file)
    {
        $this->validate(request(), [
            'files'          => 'required|file',
            'is_sertificate' => 'nullable|boolean'
        ]);
        $filePath = $course->files()->whereKey($file->id)->first()->file;

        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }

        $course->updateFile($file, request()->file('files'));

        return response()->json($course->fresh('files'));
    }
}
