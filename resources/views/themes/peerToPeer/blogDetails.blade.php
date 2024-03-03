@extends($theme.'layouts.app')
@section('title',trans('Blog Details'))

@section('content')
    <!-- blog section  -->
    <section class="blog-page blog-details">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="blog-box">
                        <div class="img-box">
                            <img src="{{ $singleItem['image'] }}" class="img-fluid" alt="{{ $singleItem['title'] }}"/>
                        </div>
                        <div class="text-box">
                            <div class="date-author">
                                <span><i class="fal fa-clock"></i> {{$singleItem['date']}}</span>
                            </div>
                            <h5 class="title">@lang($singleItem['title'])</h5>
                            <p>
                                @lang($singleItem['description'])
                            </p>
                        </div>
                    </div>
                </div>
                @if (isset($popularContentDetails['blog']))
                    <div class="col-lg-4">
                        <div class="side-bar">
                            <div class="side-box">
                                <h5>@lang('Related Posts')</h5>
                                @foreach ($popularContentDetails['blog']->sortDesc() as $data)
                                    <div class="blog-box">
                                        <div class="img-box">
                                            <img class="img-fluid" src="{{ getFile(config('location.content.path') . optional($data->content)->contentMedia->description->image) }}" alt=""/>
                                        </div>
                                        <div class="text-box">
                                            <a href="{{ route('blogDetails', [slug(optional($data->description)->title), $data->content_id]) }}" class="title"
                                            >@lang($data->description->title)
                                            </a>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
