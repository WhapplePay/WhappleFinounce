<!-- blog section -->
@if(isset($contentDetails['blog']))
    <section class="blog-section">
        <div class="container">
            @if(isset($templates['blog'][0]) && $blog = $templates['blog'][0])
                <div class="row">
                    <div class="col-12">
                        <div class="header-text text-center">
                            <h5>@lang(optional($blog->description)->title)</h5>
                            <h2>@lang(optional($blog->description)->sub_title)</h2>
                            <p class="mx-auto">
                                @lang(optional($blog->description)->short_description)
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                @forelse ( $contentDetails['blog']->take(3) as $key => $item )
                    <div class="col-lg-4 col-md-6">
                        <div
                            class="blog-box"
                            data-aos="fade-right"
                            data-aos-duration="1000"
                            data-aos-anchor-placement="center-bottom">
                            <div class="img-box">
                                <img
                                    src="{{ getFile(config('location.content.path') . optional($item->content)->contentMedia->description->image) }}"
                                    class="img-fluid" alt="..."/>
                            </div>
                            <div class="text-box">
                                <div class="date-author">
                                    <span><i class="fal fa-clock"></i>@lang(dateTime($item->created_at))</span>
                                </div>
                                <a href="{{ route('blogDetails', [slug(optional($item->description)->title), $item->content_id]) }}"
                                   class="title">@lang(optional($item->description)->title)</a>
                                <p> @lang(Str::limit($item->description->description,62))</p>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </section>
@endif
