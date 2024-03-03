<!-- navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{url('/')}}"> <img
                src="{{ getFile(config('location.logoIcon.path') . 'logo.png') }}" alt="..."/></a>
        <button
            class="navbar-toggler p-0"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="far fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{menuActive('home')}}" href="{{route('home')}}">@lang('Home')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{menuActive('about')}}" href="{{route('about')}}">@lang('About')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{menuActive('buy')}}" href="{{route('buy')}}">@lang('Buy')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{menuActive('sell')}}" href="{{route('sell')}}">@lang('Sell')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{menuActive('blog')}}" href="{{route('blog')}}">@lang('Blogs')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{menuActive('contact')}}" href="{{route('contact')}}">@lang('Contact')</a>
                </li>
            </ul>
        </div>
        <!-- navbar text -->
        <span class="navbar-text">
            @auth
                <a class="user-panel" href="{{route('user.home')}}">
                  <i class="fal fa-user-circle"></i>
               </a>
                <!-- notification panel -->
                @include($theme.'partials.pushNotify')
            @endauth
            @guest
                <a href="{{route('login')}}" class="btn-custom">@lang('Sign In')</a>
            @endguest
            </span>
    </div>
</nav>
