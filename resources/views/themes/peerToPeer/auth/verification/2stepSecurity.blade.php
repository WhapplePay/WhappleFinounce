@extends($theme.'layouts.app')
@section('title',$page_title)

@section('content')
    <section class="login-section">
        <div class="container h-100">
            <div class="row h-100 justify-content-center">
                <div class="col-lg-6">
                    <div class="form-wrapper d-flex align-items-center h-100">
                        <form action="{{route('user.twoFA-Verify')}}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-12">
                                    <h5>@lang($page_title)</h5>
                                </div>
                                <div class="input-box col-12">
                                    <input type="text" name="code"  value="{{old('code')}}" class="form-control"
                                           placeholder="@lang('Code')" autocomplete="off"/>
                                    @error('code')<span class="text-danger  mt-1">{{ $message }}</span>@enderror
                                    @error('error')<span class="text-danger  mt-1">{{ $message }}</span>@enderror
                                </div>
                                <div class="input-box col-12">
                                    <button type="submit"
                                            class="btn-custom w-100">@lang('Submit')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
