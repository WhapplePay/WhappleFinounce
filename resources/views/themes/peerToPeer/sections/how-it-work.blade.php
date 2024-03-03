<!-- how-it-works -->
@if(isset($contentDetails['how-it-work']))
    <section class="how-it-works">
        <div class="container">
            @if(isset($templates['how-it-work'][0]) && $how_it_work = $templates['how-it-work'][0])
                <div class="row">
                    <div class="col-12">
                        <div class="header-text text-center">
                            <h5>@lang(optional($how_it_work->description)->title)</h5>
                            <h2>@lang(optional($how_it_work->description)->sub_title)</h2>
                            <p class="mx-auto">
                                @lang(optional($how_it_work->description)->short_description)
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row g-4">
                @forelse ( $contentDetails['how-it-work'] as $key => $item )
                    <div class="col-lg-4">
                        <div
                            class="work-box"
                            data-aos="fade-up"
                            data-aos-duration="1000"
                            data-aos-anchor-placement="center-bottom">
                            <div class="icon-box">
                                <span class="count">{{++$key}}</span>
                                <img src="{{ getFile(config('location.content.path') . optional($item->content)->contentMedia->description->image) }}" alt="..."/>
                            </div>
                            <div class="text-box">
                                <h5>@lang(optional($item->description)->title)</h5>
                                <p>@lang(optional($item->description)->short_description)</p>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </section>
@endif
