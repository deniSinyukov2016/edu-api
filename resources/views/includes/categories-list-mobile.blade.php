<select class="form-control select-category" id="mobile-category" onchange="location = this.options[this.selectedIndex].value;">
    <option value="{{route('site.categories.show')}}">Все категории</option>
    @foreach($categories as $category)
        <option {!! request('category') == $category->slug ? "selected" : '' !!}  value="{{route('site.categories.show', ['category' => $category->slug])}}">{{$category->name}}</option>
    @endforeach
</select>
