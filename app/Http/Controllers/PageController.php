<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Image;
use App\Models\Module;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        if (request()->has('search')) {
            $search = request('search');
            $courses = Course::query()
                ->where('title', 'like', '%' . $search . '%')
                ->paginate(request('per_page') ?? 6);
            return view('pages.searchCourses', compact('courses', 'search'));
        }

        $courses = Course::query()->limit(3)->with('category')->get();

        return view('pages.index', compact('courses'));
    }

    public function showCategories()
    {
        $callback = function ($q) {
            $q->whereHas('courses', function ($q) {
                $q->where('status', 1);
            });
        };

        $selectedCategories= Category::query()
            ->with(['subcategories' => $callback])
            ->whereHas('subcategories', $callback)
            ->whereNull('parent_id')
            ->when(request('category'), function ($q) {
                $q->where('slug', request('category'));
            })->paginate(request('count', 10));

        return view('pages.allCourses', compact('selectedCategories'));
    }

    public function showCourses(Category $category)
    {
        if ($category->hasParent()) {
            $category->load('parent');
        }

        $courses = $category->courses()->where('status', 1)->paginate(request('count', 6));

        return view('pages.allSubCourses', compact('category', 'courses'));
    }

    public function showCourse(Course $course)
    {
        if ($course->status === 0) {
            abort(404);
        }

        $course->load(['modules', 'targetAudiences', 'sertificates']);

        return view('pages.coursePage', compact('course'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }
}
