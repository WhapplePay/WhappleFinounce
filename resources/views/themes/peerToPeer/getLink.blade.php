@extends($theme.'layouts.app')
@section('title')
    @lang($title)
@endsection

@section('content')
    <!-- POLICY -->
    <section class="mt-5" id="policy">
        <div class="container">
            <div class="policy wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.35s">
                @lang(@$description)
            </div>
        </div>
    </section>
    <!-- /POLICY -->
@endsection
