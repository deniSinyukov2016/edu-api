@extends('layouts.app')

@section('content')
    <main class="main-content">
        <section class="course-information padding-top-40">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center category-heading padding-top-40 padding-bottom-10">
                            <h2 style="margin-bottom: 40px"> {{$course->title}} </h2>
                            <div class="course-description">
                                <span>{{$course->body}}</span>
                            </div>
                            <!-- Open The Modal -->
                            <button class="btn blue course-btn" data-toggle="modal" data-target="#courseRegister">Записаться на курс</button>
                    </div>
                </div>
                <hr class="margin-top-50">
            </div>
        </section>
        <section class="course-for padding-top-40 padding-bottom-40">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center category-heading padding-top-40 padding-bottom-10">
                        <h2 style="margin-bottom: 40px" class="no-uppercase">Курс подойдет</h2>
                        <div class="course-list">
                            <ul>
                                @foreach($course->targetAudiences as $target)
                                    <li>
                                        <span>
                                            <span class="text-uppercase dark-text">
                                                {{ $target->title }}
                                            </span>
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="course-process padding-top-40 padding-bottom-40">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center category-heading padding-top-40 padding-bottom-10">
                            <h2 class="no-uppercase" style="margin-bottom: 40px">Как проходит обучение</h2>
                            <div class="course-description">
                                <span>
                                    <span>
                                        Lorem ipsum riisqupiditate delectus eos et fugit rat quos sed sequi tenetur ullam unde vel voluptas!
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="steps">
                            <div class="step-item margin-bottom-20">
                                <div class="step margin-bottom-30"><span>1</span></div>
                                <div class="step-text text-center"><span>Изучили материал</span></div>
                            </div>
                            <div class="step-item margin-bottom-20">
                                <div class="step margin-bottom-30"><span>2</span></div>
                                <div class="step-text text-center">Прошли тестирование</div>
                            </div>
                            <div class="step-item margin-bottom-20">
                                <div class="step margin-bottom-30"><span>3</span></div>
                                <div class="step-text text-center">Получили сертификат</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="course-process padding-top-40 padding-bottom-40">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center category-heading padding-top-40 padding-bottom-10">
                            <h2 class="no-uppercase p-0" style="margin-bottom: 40px">Программа курса</h2>
                            {{--<div class="course-description">--}}
                                {{--<span><span>{{$course->title}}</span> </span>--}}
                            {{--</div>--}}
                        </div>
                        <div class="accordion-container">
                            @php $i = 1; @endphp
                            @foreach($course->lessons as $lesson)
                            <div class="set">
                                <a>

                                    Урок {{ $i }} - {{ $lesson->name }}
                                    @php $i++; @endphp
                                    <i class="fa fa-caret-down"></i>
                                </a>
                                <div class="content">
                                    <p>{{$lesson->description}}</p>
                                </div>
                            </div>
                            @endforeach

                        </div>
                        <div class="text-center margin-top-40">
                            <!-- Open The Modal -->
                            <button class="btn blue course-btn" data-toggle="modal" data-target="#courseRegister">Записаться на курс</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="course-end padding-top-40 padding-bottom-40">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center category-heading padding-top-40 padding-bottom-10">
                        <h2 class="no-uppercase p-0" style="margin-bottom: 40px">Итоги курса</h2>
                        <div class="course-description">
                            {{--<span>--}}
                                {{--<span>--}}
                                    {{--Lorem ipsum riisqupiditate delectus eos et fugit rat quos sed sequi tenetur ullam unde vel voluptas!--}}
                                {{--</span>--}}
                            {{--</span>--}}
                        </div>
                        <div>
                        <div class="owl-carousel owl-theme">
                            @if($course->sertificates->isEmpty())
                                <div> <img src="/images/library-la-trobe-study-students-159775.jpeg" alt=""> </div>
                                <div> <img src="/images/pexels-photo-374016.jpeg" alt=""> </div>
                                <div> <img src="/images/pexels-photo-267885.jpeg" alt=""> </div>
                            @else
                                @foreach($course->sertificates as $sertificate)
                                    <div> <img src="{{ $sertificate->file }}" alt="{{ $sertificate->original_name }}"></div>
                                @endforeach
                            @endif
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="course-cost padding-top-40 padding-bottom-40">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center category-heading padding-top-40 padding-bottom-10">
                        <h2 class="no-uppercase p-0" style="margin-bottom: 40px">Стоимость курса</h2>
                        <div class="cost">
                            <span>{{$course->price}}<i class="fa fa-rub"></i></span>
                        </div>
                        <div class="text-center margin-top-40">
                            <!-- Open The Modal -->
                            <button class="btn blue course-btn" data-toggle="modal" data-target="#courseRegister">Записаться на курс</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    @include('includes.course-register')
@stop