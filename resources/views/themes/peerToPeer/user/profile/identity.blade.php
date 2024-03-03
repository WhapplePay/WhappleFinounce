@extends($theme.'user.setting.index')
@section('dynamic')
    <div class="col-lg-4">
        <div class="side-bar">
            <div class="identity-confirmation">
                @if(in_array($user->identity_verify,[0,3])  )
                    @if($user->identity_verify == 3)
                        <div class="col-12">
                            <div class="bd-callout bd-callout-primary mx-2">
                                <i class="fa-3x fas fa-info-circle font-13"></i>
                                <span class="text-danger">@lang('You previous request has been rejected')</span>
                            </div>
                        </div>
                    @endif
                    <form method="post" action="{{route('user.verificationSubmit')}}"
                          enctype="multipart/form-data" class="mt-0">
                        @csrf
                        <div class="row g-4">
                            <div class="col-12">
                                <h4>@lang('Confirm Identity')</h4>
                            </div>
                            <div class="row">
                                <div class="input-box col-12 mb-3">
                                    <label for="">@lang('Identity Type')</label>
                                    <select name="identity_type" id="identity_type" class="form-select"
                                            aria-label="Default select example">
                                        <option value="" selected disabled>@lang('Select Type')</option>
                                        @foreach($identityFormList as $sForm)
                                            <option
                                                value="{{$sForm->slug}}" {{ old('identity_type', @$identity_type) == $sForm->slug ? 'selected' : '' }}>@lang($sForm->name)</option>
                                        @endforeach
                                    </select>
                                    @error('identity_type')
                                    <div class="error text-danger">@lang($message) </div>
                                    @enderror
                                </div>
                            </div>
                            @if(isset($identityForm))
                                @foreach($identityForm->services_form as $k => $v)
                                    @if($v->type == "text")
                                        <div class="row">
                                            <div class="col-12">
                                                <label
                                                    for="{{$k}}">{{trans($v->field_level)}} @if($v->validation == 'required')
                                                        <span class="text-danger">*</span>
                                                    @endif
                                                </label>
                                                <div class="form-group input-box mb-3">
                                                    <input type="text" name="{{$k}}"
                                                           class="form-control "
                                                           value="{{old($k)}}" id="{{$k}}"
                                                           @if($v->validation == 'required') required @endif>

                                                    @if($errors->has($k))
                                                        <div
                                                            class="error text-danger">@lang($errors->first($k)) </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($v->type == "textarea")
                                        <div class="row">
                                            <div class="col-12">
                                                <label
                                                    for="{{$k}}">{{trans($v->field_level)}} @if($v->validation == 'required')
                                                        <span
                                                            class="text-danger">*</span>
                                                    @endif
                                                </label>
                                                <div class="form-group input-box mb-3">
                                                        <textarea name="{{$k}}" id="{{$k}}"
                                                                  class="form-control "
                                                                  rows="5"
                                                                  placeholder="{{trans('Type Here')}}"
                                                                          @if($v->validation == 'required')@endif>{{old($k)}}</textarea>
                                                    @error($k)
                                                    <div class="error text-danger">
                                                        {{trans($message)}}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($v->type == "file")
                                        <div class="row">
                                            <div class="col-12">
                                                <label>{{trans($v->field_level)}} @if($v->validation == 'required')
                                                        <span class="text-danger">*</span>
                                                    @endif
                                                </label>
                                                <div class="form-group input-box">
                                                    <br>
                                                    <div class="fileinput fileinput-new "
                                                         data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail "
                                                             data-trigger="fileinput">
                                                            <img class="wh-200-150"
                                                                 src="{{ getFile(config('location.default')) }}"
                                                                 alt="...">
                                                        </div>
                                                        <div
                                                            class="fileinput-preview fileinput-exists thumbnail wh-200-150 "></div>

                                                        <div class="img-input-div">
                                                                    <span class="btn btn-success btn-file">
                                                                        <span
                                                                            class="fileinput-new "> @lang('Select') {{$v->field_level}}</span>
                                                                        <span
                                                                            class="fileinput-exists"> @lang('Change')</span>
                                                                        <input type="file" name="{{$k}}"
                                                                               value="{{ old($k) }}" accept="image/*"
                                                                               @if($v->validation == "required")@endif>
                                                                    </span>
                                                            <a href="#"
                                                               class="btn btn-remove fileinput-exists"
                                                               data-dismiss="fileinput"> @lang('Remove')</a>
                                                        </div>
                                                    </div>
                                                    @error($k)
                                                    <div class="error text-danger">
                                                        {{trans($message)}}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                <div class="col-12">
                                    <button type="submit"
                                            class="btn-custom">@lang('Submit')</button>
                                </div>
                            @endif
                        </div>
                    </form>
                @elseif($user->identity_verify == 1)
                    <div class="row">
                        <div class="col-12">
                            <div class="bd-callout bd-callout-primary mx-2">
                                <i class="fa-3x fas fa-info-circle font-13"></i>
                                <span class="text-warning">@lang('Your KYC submission has been pending')</span>
                            </div>
                        </div>
                    </div>
                @elseif($user->identity_verify == 2)
                    <div class="row">
                        <div class="col-12">
                            <div class="bd-callout bd-callout-primary mx-2">
                                <i class="fa-3x fas fa-info-circle font-13"></i>
                                <span class="text-success">@lang('Your KYC already verified')</span></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@push('css-lib')
    <link rel="stylesheet" href="{{asset($themeTrue.'css/bootstrap-fileinput.css')}}">
@endpush

@push('extra-js')
    <script src="{{asset($themeTrue.'js/bootstrap-fileinput.js')}}"></script>
@endpush
@push('script')
    <script>
        "use strict";
        $(document).on('change', "#identity_type", function () {
            let value = $(this).find('option:selected').val();
            window.location.href = "{{route('user.identityVerify')}}/?identity_type=" + value
        });
    </script>
@endpush
