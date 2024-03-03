@extends($theme.'layouts.app')
@section('title',trans('Home'))

@section('content')

    @include($theme.'sections.homeBanner')
    @include($theme.'sections.about-us')
    @include($theme.'sections.how-it-work')
    @include($theme.'sections.buy-sell')
    @include($theme.'sections.feature')
    @include($theme.'sections.faq')
    @include($theme.'sections.testimonial')
    @include($theme.'sections.blog')
    @include($theme.'sections.call')

@endsection
