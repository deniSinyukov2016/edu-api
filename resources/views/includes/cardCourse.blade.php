@foreach($courses as $course)
    <!-- Event Column -->
    <div class="col-lg-4 col-md-4 col-xs-6 r-full-width margin-bottom-20">
        <div class="course-column tc-hover margin-bottom-20">
            <div class="course-img">
                @if(isset($course->images->image))
                    <img src="{{$course->images->image}}" alt="">
                @else
                    <img src="/images/pexels-photo-374016.jpeg" alt="">
                @endif
            </div>
            <div class="course-detail">

                <span class="date"><i class="fa fa-book"></i>{{$course->category->name}}</span>

                <h3><a href="#">{{$course->title}}</a></h3>
                <p>{{ str_limit($course->body) }}</p>
                <a class="btn blue sm" href="{{route('site.course.show', $course)}}">Перейти<i class="fa fa-angle-right"></i></a>
            </div>
        </div>
    </div>
@endforeach