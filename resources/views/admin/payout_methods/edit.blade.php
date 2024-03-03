@extends('admin.layouts.app')
@section('title',trans($page_title))
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
                <div class="card-body">

                    <div class="media mb-4 justify-content-end">
                        <a href="{{route('admin.payout.method')}}" class="btn btn-sm  btn-primary mr-2">
                            <span><i class="fas fa-arrow-left"></i> @lang('Back')</span>
                        </a>
                    </div>


                    <form method="post" action="{{route('admin.payout.method.edit', $payoutMethod->id)}}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6 col-6">
                                <label>{{trans('Name')}}</label>
                                <input type="text" class="form-control"
                                       name="name"
                                       value="{{ old('name', $payoutMethod->name ?? '') }}" required>

                                @error('name')
                                <span class="text-danger">{{ $message  }}</span>
                                @enderror
                            </div>
                        </div>

                        @if($payoutMethod->parameters)
                            <div class="row mt-4">
                                @foreach ($payoutMethod->parameters as $key => $parameter)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label
                                                for="{{ $key }}">{{ __(strtoupper(str_replace('_',' ', $key))) }}</label>
                                            <input type="text" name="{{ $key }}"
                                                   value="{{ old($key, $parameter) }}"
                                                   id="{{ $key }}"
                                                   class="form-control @error($key) is-invalid @enderror">
                                            <div class="invalid-feedback">
                                                @error($key) @lang($message) @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <div class="row justify-content-between">
                            <div class="col-sm-6 col-md-3">
                                <div class="image-input ">
                                    <label for="image-upload" id="image-label"><i class="fas fa-upload"></i></label>
                                    <input type="file" name="image" placeholder="@lang('Choose image')" id="image">
                                    <img id="image_preview_container" class="preview-image"
                                         src="{{ getFile(config('location.payoutMethod.path').$payoutMethod->image)}}"
                                         alt="preview image">
                                </div>
                                @error('image')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            @if($payoutMethod->is_sandbox)
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group ">
                                        <label>@lang('Test Environment')</label>
                                        <div class="custom-switch-btn">
                                            <input type='hidden' value='1' name='environment'>
                                            <input type="checkbox" name="environment" class="custom-switch-checkbox"
                                                   id="environment"
                                                   value="0" {{($payoutMethod->environment == 0) ? 'checked':''}}>
                                            <label class="custom-switch-checkbox-label" for="environment">
                                                <span class="custom-switch-checkbox-inner"></span>
                                                <span class="custom-switch-checkbox-switch"></span>
                                            </label>
                                        </div>

                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="row addedField">
                            @if($payoutMethod->input_form)
                                @foreach ($payoutMethod->input_form as $k => $v)
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="input-group">

                                                <input name="field_name[]" class="form-control"
                                                       type="text" value="{{$v->field_level}}" required
                                                       placeholder="{{trans('Field Name')}}">

                                                <select name="type[]" class="form-control  ">
                                                    <option value="text"
                                                            @if($v->type == 'text') selected @endif>{{trans('Input Text')}}</option>
                                                    <option value="textarea"
                                                            @if($v->type == 'textarea') selected @endif>{{trans('Textarea')}}</option>
                                                    <option value="file"
                                                            @if($v->type == 'file') selected @endif>{{trans('File upload')}}</option>
                                                </select>

                                                <select name="validation[]" class="form-control  ">
                                                    <option value="required"
                                                            @if($v->validation == 'required') selected @endif>{{trans('Required')}}</option>
                                                    <option value="nullable"
                                                            @if($v->validation == 'nullable') selected @endif>{{trans('Optional')}}</option>
                                                </select>

                                                <span class="input-group-btn">
                                                    <button class="btn btn-danger  delete_desc" type="button">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <button type="submit"
                                class="btn  btn-primary btn-block mt-3">@lang('Save Changes')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('js')
    <script>
        $(document).ready(function (e) {
            "use strict";

            $("#generate").on('click', function () {
                var form = `<div class="col-md-12">
        <div class="form-group">
            <div class="input-group">
                <input name="field_name[]" class="form-control " type="text" value="" required
                       placeholder="@lang("Field Name")">

                <select name="type[]" class="form-control ">
                    <option value="text">@lang("Input Text")</option>
                    <option value="textarea">@lang("Textarea")</option>
                    <option value="file">@lang("File upload")</option>
                </select>

                <select name="validation[]" class="form-control  ">
                    <option value="required">@lang('Required')</option>
                    <option value="nullable">@lang('Optional')</option>
                </select>

                <span class="input-group-btn">
                    <button class="btn btn-danger  delete_desc" type="button">
                        <i class="fa fa-times"></i>
                    </button>
                </span>
            </div>
        </div>
    </div>`;
                $('.addedField').append(form)
            });


            $(document).on('click', '.delete_desc', function () {
                $(this).closest('.input-group').parent().remove();
            });


            $('#image').on('change',function () {
                var reader = new FileReader();
                reader.onload = (e) => {
                    $('#image_preview_container').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });

            $(document).ready(function () {
                $('select').select2({
                    selectOnClose: true
                });
            });
        });

    </script>
@endpush
