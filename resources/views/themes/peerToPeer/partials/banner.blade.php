<style>
    #page-banner {
        background-image: linear-gradient(90deg, rgba(7, 11, 40, 0.65) 0%, rgba(7, 11, 40, 0.65) 100%), url({{getFile(config('location.logo.path').'banner.jpg')}});
    }
</style>
@if(!request()->routeIs('home'))
    <!-- banner section -->
    <section class="banner-section">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <h3>@yield('title')</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">@lang('Home')</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
@endif
