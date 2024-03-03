<!-- testimonial section -->
@if(isset($contentDetails['testimonial']))
    <section class="testimonial-section">
        <div class="container">
            @if(isset($templates['testimonial'][0]) && $testimonial = $templates['testimonial'][0])
                <div class="row">
                    <div class="col-12">
                        <div class="header-text text-center">
                            <h5>@lang(optional($testimonial->description)->title)</h5>
                            <h2>@lang(optional($testimonial->description)->sub_title)</h2>
                            <p class="mx-auto">
                                @lang(optional($testimonial->description)->short_description)
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-12">
                    <div class="testimonials owl-carousel">
                        @forelse ( $contentDetails['testimonial'] as $key => $item )
                            <div
                                class="review-box"
                                data-aos="fade-up"
                                data-aos-duration="1000"
                                data-aos-anchor-placement="center-bottom">
                                <div class="top">
                                    <img
                                        src="{{ getFile(config('location.content.path') . optional($item->content)->contentMedia->description->image) }}"
                                        alt="..."/>
                                    <div>
                                        <h5>@lang(optional($item->description)->name)</h5>
                                        <span class="title">@lang(optional($item->description)->designation)</span>
                                        <span class="bar"></span>
                                        <span>
                                             @if(optional($item->description)->rating)
                                                @for($i=0; $i<$item->description->rating; $i++)
                                                    <i class="fas fa-star"></i>
                                                @endfor
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <p>
                                    @lang(optional($item->description)->description)
                                </p>
                                <img src="{{asset($themeTrue.'/images/icon/right-quote.png')}}" alt="" class="quote"/>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
