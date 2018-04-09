<div class="col-sm-12">
    <!-- Main Heading -->
    <div class="category-heading padding-top-40 padding-bottom-10">
        <h2>Категории</h2>
        <div class="flex-line margin-bottom-20 hidden-xs" id="listCategory">
            <a href="{{route('site.categories.show')}}"><span  {!! request('category') ? "" : "class='check'" !!} >Все категории</span></a>
            @include('includes.categories-list')
        </div>
        <div class="find-courses style-1 margin-bottom-20 hidden-sm hidden-md hidden-lg">
            @include('includes.categories-list-mobile')
        </div>
    </div>
</div>