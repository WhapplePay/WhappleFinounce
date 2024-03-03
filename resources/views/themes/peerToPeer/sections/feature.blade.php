<!-- feature section -->
@if(isset($contentDetails['feature']))
    <section class="feature-section">
        <div class="container">
            @if(isset($templates['feature'][0]) && $feature = $templates['feature'][0])
                <div class="row">
                    <div class="col-12">
                        <div class="header-text text-center">
                            <h5>@lang(optional($feature->description)->title)</h5>
                            <h2>@lang(optional($feature->description)->sub_title)</h2>
                            <p class="mx-auto">
                                @lang(optional($feature->description)->short_description)
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row g-4">
                @forelse ( $contentDetails['feature'] as $key => $item )
                    <div class="col-md-6 col-lg-4">
                        <div
                            class="feature-box"
                            data-aos="fade-up"
                            data-aos-duration="1000"
                            data-aos-anchor-placement="center-bottom">
                            <div class="icon-box">
                                <img src="{{ getFile(config('location.content.path') . optional($item->content)->contentMedia->description->image) }}" alt="..."/>
                            </div>
                            <h5>@lang(optional($item->description)->title)</h5>
                            <p>
                                @lang(optional($item->description)->information)
                            </p>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </section>
@endif
