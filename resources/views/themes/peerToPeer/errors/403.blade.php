@extends($theme.'layouts.error')
@section('title','403 Forbidden')
@section('content')
    <section class="not-found">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col">
                    <div class="text-box text-center">
                        <img src="{{asset($themeTrue.'/images/404.svg')}}" alt="..." />
                        <h1>{{trans('Forbidden')}}</h1>
                        <p>{{trans("You don't have permission to access ‘/’ on this server")}}</p>
                        <a href="{{url('/')}}">@lang('Back To Home')</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /ERROR -->
@endsection
