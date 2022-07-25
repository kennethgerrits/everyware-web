<nav class="navbar navbar-expand-lg bg-dark">
    <a class="navbar-brand nav-link-custom" href="/">{{__(('navbar.appname'))}}</a>
    <button class="navbar-dark navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            @if(Route::currentRouteName() != null)
            <li class="{{ (str_contains(Route::currentRouteName(), 'dashboard')) ? 'menu-item-active' : 'nav-item nav-item-big' }}">
                <a class=" nav-link nav-link-custom" href="/"><span class="fa fa-home"></span> Home</a>
            </li>
            <li class="{{ (str_contains(Route::currentRouteName(), 'groups')) ? 'menu-item-active' : 'nav-item nav-item-big' }}">
                <a class="nav-link nav-link-custom" href="{{route('groups.index')}}"><span class="fa fa-user-friends"></span> {{ __('navbar.my_school') }}</a>
            </li>
            <li class="{{ (str_contains(Route::currentRouteName(), 'material')) 
                        || (str_contains(Route::currentRouteName(), 'templates'))
                        || (str_contains(Route::currentRouteName(), 'category'))
                            ? 'menu-item-active' : 'nav-item nav-item-big' }}">
                <a class="nav-link nav-link-custom" href="{{route('material.index')}}"><span class="fa fa-book"></span> {{ __('navbar.curriculum') }}</a>
            </li>
            <li class="{{ (str_contains(Route::currentRouteName(), 'wordlist')) ? 'menu-item-active' : 'nav-item nav-item-big' }}">
                <a class="nav-link nav-link-custom" href="{{route('wordlists.index')}}"><span class="fa fa-list"></span> {{ __('navbar.word_lists') }}</a>
            </li>
            <li class="{{ (str_contains(Route::currentRouteName(), 'stats')) ? 'menu-item-active' : 'nav-item nav-item-big' }}">
                <a class="nav-link nav-link-custom" href="{{route('stats.index')}}"><span class="fa fa-chart-bar"></span> {{ __('navbar.statistics') }}</a>
            </li>
            @endif
        </ul>
        @auth
        <ul class="navbar-nav my-2 my-lg-0">
            <li class="nav-item nav-item-big">
                <form action="{{asset('/logout')}}" method="post">
                    @csrf
                    <button class="btn nav-link nav-link-logout"><span class="fa fa-power-off"></span> {{ __('navbar.logout') }}</button>
                </form>
            </li>
        </ul>
        @endauth
        {{-- <form class="form-inline my-2 my-lg-0">--}}
        {{-- <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">--}}
        {{-- <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>--}}
        {{-- </form>--}}
    </div>
</nav>