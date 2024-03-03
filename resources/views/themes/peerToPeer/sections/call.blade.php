<!-- call to section -->
@if(isset($templates['call'][0]) && $call = $templates['call'][0])
    <section class="call-to-section">
        <div class="container">
            <div class="box" data-aos="fade-up" data-aos-duration="1000" data-aos-anchor-placement="center-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2>@lang(optional($call->description)->title)</h2>
                    </div>
                    <div class="col-md-6">
                        <a href="{{$call->templateMedia()->button_link}}" class="btn-custom">@lang(optional($call->description)->button_name)</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
