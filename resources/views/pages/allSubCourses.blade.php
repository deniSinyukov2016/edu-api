@extends('layouts.app')

@section('content')
    <main class="main-content">
        <section class="comming-events padding-top-40 padding-bottom-40 gray-bg" style="min-height: 454px">
            <div class="container">
                <div class="breadcrumb">
                    <div class="row">
                        <div class="top-breadcrumbs">
                            <a href="{{route('site.page.index')}}"><span>Главная</span> </a> > <a href="{{route('site.categories.show')}}"><span> {{$category->parent->name}} </span></a> > <span>{{$category->name}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <!-- Main Heading -->
                <div class="row">
                    <div class="col-sm-12">
                        <!-- Main Heading -->
                        <div class="category-heading padding-top-40 padding-bottom-10">
                            <h2>{{$category->name}}</h2>
                        </div>
                    </div>
                </div>
                <div class="row row-flex">
                    @include('includes.cardCourse')
                </div>
                <div style="text-align: center">{{ $courses->render() }}</div>
            </div>
        </section>
    </main>
@stop