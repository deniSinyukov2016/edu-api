@extends('layouts.app')

@section('content')
    <main class="main-content">
        <section class="comming-events padding-top-40 padding-bottom-40 gray-bg">

            <div class="container">
                <div class="breadcrumb">
                    <div class="row">
                        <div class="top-breadcrumbs">
                            <a href="{{route('site.page.index')}}"><span>Главная</span> </a> > <a href="{{route('site.categories.show')}}" style="padding-left: 5px">  <span>Категории</span></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @include('includes.category-heading')
                </div>
            <!-- Main Heading -->
                <!-- Eventes Row -->
            @foreach($selectedCategories as $category)
                <div class="row category-list padding-top-30">
                    <div class="col-sm-12">
                        <div class="category-heading">
                            <h3 id="{{$category->name}}">{{$category->name}}</h3>
                        </div>
                    </div>
                <!-- Event Column -->
                    @if($category->subcategories)
                        @foreach($category->subcategories as $subcategory)
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 r-full-width">
                            <div class="courses-tile category-tile event-column z-depth-2">
                                @if (isset($subcategory->parent->getImage()->image))
                                    <img src="{{$subcategory->parent->getImage()->image}}" alt="">
                                @else
                                    <img src="/images/pexels-photo-267885.jpeg" alt="">
                                @endif
                                <div class="overlay-category">
                                    <div class="inner-overlay">
                                        <h4 style="padding: 5px;" class="margin-bottom-20 text-white">{{$subcategory->name}}</h4>
                                        <ul class="text-center">
                                            <li><a class="btn blank white-btn inner-btn overlay-btn" href="{{route('site.courses.index', $subcategory)}}">Перейти<i class="fa fa-arrow-right" style="font-weight: 300;"></i></a></li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- Event Column -->
                        @endforeach
                    @endif
                </div>
            @endforeach

            <!-- Event Column -->
            </div>
        </section>

    </main>
@stop