<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddImageRequest;
use App\Jobs\ImagePodcast;
use App\Models\Category;

class ImageCategoryController extends Controller
{
    /**
     * @apiDesc  Upload image for category
     * @apiParam file $image in_query | Upload image
     * @apiParam integer $category in_path | Category id  $data = [
            'file'          => $this->getFileDir() . $uplfile->hashName(),
            'type'          => $uplfile->getMimeType(),
            'size'          => $uplfile->getSize(),
            'original_name' => $uplfile->getClientOriginalName(),
        ];

     * @apiResp  200 | Whatever message is send from backend on success
     * @apiErr   401 | Unauthorized .
     *
     * @param AddImageRequest $request
     * @param Category $category
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AddImageRequest $request, Category $category)
    {
        dispatch($imagePodcast = new ImagePodcast($request, $category));

        return response()->json($imagePodcast->getFileUrls());
    }

    /**
     * @apiDesc Delete image for category
     * @apiParam integer $category in_path | Category id
     * @apiResp 204 | Whatever message is send from backend on success
     * @apiErr  401 | Unauthorized
     * @apiErr  404 | Not found
     *
     * @param Category $category
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Category $category)
    {
        return response()->json($category->deleteImage(), 204);
    }
}
