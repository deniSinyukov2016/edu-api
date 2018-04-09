@extends('layouts.app')

@section('content')
    <main class="main-content">
        <section class="comming-events padding-top-40 padding-bottom-40 gray-bg" style="min-height: 454px;">

            <div class="container">
                <div class="breadcrumb">
                    <div class="row">
                        <div class="top-breadcrumbs">
                            <a href="{{route('site.page.index')}}"><span>Главная</span> </a> > <span>Результаты поиска</span>
                        </div>
                    </div>
                </div>
                @if (!$courses->count())
                    <h1 class="search-result-title text-center ">Извините, по вашему запросу ничего не найдено</h1>
                    <div>
                    </div>
                    @else
                    <h1 class="search-result-title margin-bottom-40">Результаты поиска для <span class="bold">"{{$search}}"</span></h1>
                    <div class="container">
                        <div class="row row-flex">
                            @include('includes.cardCourse')
                        </div>
                        <div style="text-align: center">{{ $courses->appends(request()->input()) }}</div>
                    </div>
                @endif
               {{-- <h1 class="search-result-title">Результаты поиска для <span class="bold">"{{$search}}"</span></h1>
                <div class="container">
                        <div class="row row-flex">
                            @include('includes.cardCourse')
                        </div>
                    <div style="text-align: center">{{ $courses->appends(request()->input()) }}</div>
                </div>--}}

            </div>
        </section>

    </main>
@stop