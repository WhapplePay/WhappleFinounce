<!-- about section -->
@if(isset($templates['about-us'][0]) && $about_us = $templates['about-us'][0])
    <section class="about-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div
                        class="img-box"
                        data-aos="fade-right"
                        data-aos-duration="1000"
                        data-aos-anchor-placement="center-bottom">
                        <img src="{{getFile(config('location.content.path').@$about_us->templateMedia()->image)}}"
                             class="img-fluid rounded-circle" alt="..."/>
                        <a class="play-vdo" href="{{@$about_us->templateMedia()->video_link}}" data-fancybox="video">
                            <i class="fas fa-play"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div
                        class="text-box"
                        data-aos="fade-left"
                        data-aos-duration="1000"
                        data-aos-anchor-placement="center-bottom">
                        <div class="header-text mb-5">
                            <h5>@lang(optional($about_us->description)->title)</h5>
                            <h2>@lang(optional($about_us->description)->sub_title)</h2>
                        </div>
                        <p>
                            @lang(optional($about_us->description)->short_description)
                        </p>
                        <ul>
                            @if(isset($contentDetails['about-us']))
                                @forelse ( $contentDetails['about-us'] as $key => $item )
                                    <li>
                                        <i class="fal fa-check"></i>
                                        @lang(optional($item->description)->short_description)
                                    </li>
                                @empty
                                @endforelse
                            @endif
                        </ul>
                        <a href="{{@$about_us->templateMedia()->button_link}}">
                            <button
                                class="btn-custom mt-4">@lang(optional($about_us->description)->button_name)</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
