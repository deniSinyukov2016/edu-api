<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddImageRequest;
use App\Models\Course;
use App\Models\Image;
use Storage;

class ImageController extends Controller
{
    /**
     * @apiDesc Upload image for course
     * @apiParam file $image in_query | Upload image
     * @apiParam integer $course in_path | Course id
     * @apiResp 200 | Whatever message is send from backend on success
     * @apiErr  401 | Unauthorized .
     *
     * @param AddImageRequest $request
     * @param Course $course
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(AddImageRequest $request, Course $course)
    {
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            /** @var Image $image */
            $image = $course->addImage($request->file('image'));

            return response()->json($image->image);
        }
    }

    /**
     * @apiDesc Delete image for course
     * @apiParam integer $course in_path | Course id
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
        return response()->json($course->deleteImage(), 204);
    }

    /**
     * @apiDesc Update image for course
     * @apiParam integer $course in_path | Course id
     * @apiParam integer $image in_path | Image id
     * @apiParam file $image in_query | File
     * @apiResp 200 | Whatever message is send from backend on success
     * @apiErr  401 | Unauthorized
     * @apiErr  404 | Not found
     *
     * @param AddImageRequest $request
     * @param Course $course
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(AddImageRequest $request, Course $course)
    {
        $filePath = $course->images->image;

        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }

        $course->imageUpdate($request->file('image'));

        return response()->json($course->fresh('images'));
    }
}
