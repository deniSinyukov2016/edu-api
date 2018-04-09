<header>

    <div class="nav-holder style-2">
        <div class="container-fluid containerPadding">
            <div class="row flex-header">
                <div class="col-md-2 col-5">
                    <div class="logo">
                        <a href="{{asset('/')}}"><img src="/images/logo-2.png" alt=""></a>
                    </div>
                </div>
                <div class="col-md-5 col-6">
                    <div class="search-nd-cart">
                        <ul>
                            <li class="menu-link-holder"><a href="#menu" class="menu-link circle-btn"><i class="fa fa-bars"></i></a></li>
                        </ul>
                    </div>
                    <div class="nav-list">
                        <ul>
                            <li @if(request()->routeIs('site.categories.show')) class="active" @endif>
                                <a href="{{route('site.categories.show')}}">Все курсы</a>
                            </li>
                            <li @if(request()->routeIs('site.about.show')) class="active" @endif>
                                <a href="{{route('site.about.show')}}">О EDU</a>
                            </li>
                            <li @if(request()->routeIs('site.contact.show')) class="active" @endif>
                                <a href="{{route('site.contact.show')}}">Контакты</a>
                            </li>
                           {{-- <li>
                                <form action="/" method="GET">
                                    <input type="text" name="search">
                                    <button type="submit">search</button>
                                </form>
                            </li>--}}
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 hidden-md-down">
                    <form action="/" method="GET" class="form-search">
                        <input class="search-input" type="text" name="search" required style="outline: none">
                        <button type="submit" class="fa fa-search search link" href="#"></button>
                    </form>

                </div>
                <div class="col-md-1 hidden-md-down" style="text-align: right">
                    <div class="sign-in">
                        <ul class="tools-nav">
                            <li class="">
                                <a id="sign-in">Войти</a>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>

        </div>
    </div>
</header>