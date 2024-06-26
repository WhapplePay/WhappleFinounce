@extends($theme.'layouts.error')
@section('title','419')
@section('content')
    <section class="not-found">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col">
                    <div class="text-box text-center">
                        <img src="{{asset($themeTrue.'/images/404.svg')}}" alt="..." />
                        <h1>@lang('419')</h1>
                        <p>@lang("Sorry, your session has expired")</p>
                        <a href="{{url('/')}}">@lang('Back To Home')</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
