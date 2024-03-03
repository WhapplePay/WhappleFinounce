@extends($theme.'layouts.app')
@section('title', trans($title))

@section('content')
    <!-- blog section  -->
    @if(isset($contentDetails['blog']))
        <section class="blog-page blog-details">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        @forelse ( $contentDetails['blog'] as $key => $item )
                            <div class="blog-box">
                                <div class="img-box">
                                    <img
                                        src="{{ getFile(config('location.content.path') . optional($item->content)->contentMedia->description->image) }}"
                                        class="img-fluid" alt="..."/>
                                </div>
                                <div class="text-box">
                                    <div class="date-author">
                                        <span><i class="fal fa-clock"></i> @lang(dateTime($item->created_at))</span>
                                    </div>
                                    <h5 class="title">@lang(optional($item->description)->title)</h5>
                                    <p>
                                        @lang(Str::limit(optional($item->description)->description,256))
                                    </p>
                                    <a href="{{ route('blogDetails', [slug(@$item->description->title), $item->content_id]) }}"
                                       class="btn-custom mt-3">@lang('Read more')</a>
                                </div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                    <div class="col-lg-4">
                        <div class="side-bar">
                            <div class="side-box">
                                <form  action="{{route('blog')}}" method="get">
                                    <h5>@lang('Search')</h5>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search" value="{{request()->search}}" placeholder="@lang('search')"/>
                                        <button type="submit"><i class="fal fa-search" aria-hidden="true"></i></button>
                                    </div>
                                </form>
                            </div>
                            <div class="side-box">
                                <h5>@lang('Recent Posts')</h5>
                                @forelse ( $contentDetails['blog']->sortBy('created_at') as $key => $item )
                                    <div class="blog-box">
                                        <div class="img-box">
                                            <img class="img-fluid" src="{{ getFile(config('location.content.path') . optional($item->content)->contentMedia->description->image) }}" alt="..."/>
                                        </div>
                                        <div class="text-box">
                                            <a href="{{ route('blogDetails', [slug(@$item->description->title), $item->content_id]) }}" class="title"
                                            >@lang(optional($item->description)->title)
                                            </a>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
