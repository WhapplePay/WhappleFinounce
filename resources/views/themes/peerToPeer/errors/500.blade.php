@extends($theme.'layouts.error')
@section('title','500')
@section('content')
    <section class="not-found">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col">
                    <div class="text-box text-center">
                        <img src="{{asset($themeTrue.'/images/404.svg')}}" alt="..." />
                        <h1>@lang('Internal Server Error')</h1>
                        <p>@lang("The server encountered an internal error misconfiguration and was unable to complate your request. Please contact the server administrator.")</p>
                        <a href="{{url('/')}}">@lang('Back To Home')</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
