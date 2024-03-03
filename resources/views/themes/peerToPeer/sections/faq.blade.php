<!-- faq section -->
@if(isset($contentDetails['faq']))
    <section class="faq-section">
        <div class="container">
            @if(isset($templates['faq'][0]) && $faq = $templates['faq'][0])
                <div class="row">
                    <div class="col-12">
                        <div class="header-text text-center">
                            <h5>@lang(optional($faq->description)->title)</h5>
                            <h2>@lang(optional($faq->description)->sub_title)</h2>
                            <p class="mx-auto">
                                @lang(optional($faq->description)->short_description)
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-6">
                    <div
                        class="img-box"
                        data-aos="fade-right"
                        data-aos-duration="1000"
                        data-aos-anchor-placement="center-bottom">
                        <img src="{{getFile(config('location.content.path').@$faq->templateMedia()->image)}}"
                             class="img-fluid" alt="..."/>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div
                        class="accordion"
                        id="accordionExample"
                        data-aos="fade-left"
                        data-aos-duration="1000"
                        data-aos-anchor-placement="center-bottom">
                        @forelse ( $contentDetails['faq'] as $key => $item )
                            <div class="accordion-item">
                                <h5 class="accordion-header" id="heading{{$key}}">
                                    <button
                                        class="accordion-button collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{$key}}"
                                        aria-expanded="false"
                                        aria-controls="collapse{{$key}}">
                                        @lang(@$item->description->title)
                                    </button>
                                </h5>
                                <div
                                    id="collapse{{$key}}"
                                    class="accordion-collapse collapse"
                                    aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        @lang(optional($item->description)->description)
                                    </div>
                                </div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
