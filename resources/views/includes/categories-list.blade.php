@foreach($categories as $category)
    <a href="{{route('site.categories.show', ['category' => $category->slug])}}">
        <span  {!! request('category') == $category->slug ? "class='check'" : '' !!}>{{$category->name}}</span>
    </a>
@endforeach