<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\File;

class SertificateController extends Controller
{
    /**
     * @apiDesc     Show all sertificates
     * @apiParam    integer $count in_query | Set count showing
     * @apiResp     200 | Whatever message is send from backend on success
     * @apiErr      401 | Unauthorized .
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        /** @var File $sertificates */
        $sertificates = File::query()->where('is_sertificate', true);

        return response()->json($sertificates->paginate(request('count', 10)));
    }

    /**
     * @apiDesc     Show all sertificates for course
     * @apiParam    integer $course in_path | Course id
     * @apiResp     200 | Whatever message is send from backend on success
     * @apiErr      401 | Unauthorized .
     *
     * @param Course $course
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Course $course)
    {
        return response()->json($course->sertificates()->get());
    }
}
