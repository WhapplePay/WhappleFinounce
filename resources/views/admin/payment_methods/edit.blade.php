@extends('admin.layouts.app')
@section('title')
    {{ $page_title }}
@endsection
@section('content')
    <div class="container-fluid">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card card-primary shadow">
                    <div class="card-body">
                        <form method="post" action="{{ route('admin.update.payment.methods', $method->id) }}"
                              class="needs-validation base-form" novalidate="" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="my-2 section-title">
                                @lang('Edit') {{ strtoupper($method->name) }}
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4 col-6">
                                    <label>@lang('Name')</label>
                                    <input type="text" class="form-control "
                                           name="name"
                                           value="{{ old('name', $method->name ? : '') }}">
                                    @if ($errors->has('name'))
                                        <span class="invalid-text">
                                                {{ $errors->first('name') }}
                                            </span>
                                    @endif
                                </div>
                                <div class="form-group col-md-4 col-4">
                                    <label>@lang('Border Color')</label>
                                    <input type="color" class="form-control "
                                           name="color" value="{{$method->color}}">
                                    @error('color')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6 col-md-4">
                                    <div class="image-input ">
                                        <label for="image-upload" id="image-label"><i class="fas fa-upload"></i></label>
                                        <input type="file" name="image" placeholder="@lang('Choose image')" id="image">
                                        <img id="image_preview_container" class="preview-image"
                                             src="{{ getFile(config('location.gateway.path').$method->image)}}"
                                             alt="preview image">
                                    </div>
                                    @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-lg-3 col-md-4">
                                    <label>@lang('Status')</label>

                                    <div class="custom-switch-btn">
                                        <input type='hidden' value='1' name='status'>
                                        <input type="checkbox" name="status" class="custom-switch-checkbox" id="status"
                                               value="0" <?php if ($method->status == 0):echo 'checked'; endif ?> >
                                        <label class="custom-switch-checkbox-label" for="status">
                                            <span class="custom-switch-checkbox-inner"></span>
                                            <span class="custom-switch-checkbox-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-end mb-4">
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <a href="javascript:void(0)" class="btn btn-sm btn-success float-right mt-3"
                                           id="generate"><i
                                                class="fa fa-plus-circle"></i> @lang('Add Field')</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row addedField">
                                @if($method->input_form)
                                    @foreach ($method->input_form as $k => $v)
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="input-group">

                                                    <input name="field_name[]" class="form-control"
                                                           type="text" value="{{$v->label}}" required
                                                           placeholder="{{trans('Field Name')}}">

                                                    <select name="type[]" class="form-control">
                                                        <option value="text"
                                                                @if($v->type == 'text') selected @endif>{{trans('Input Text')}}</option>
                                                        <option value="textarea"
                                                                @if($v->type == 'textarea') selected @endif>{{trans('Textarea')}}</option>
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
                                    class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3">@lang('Save Changes')
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        "use strict";

        $(document).ready(function () {
            setCurrency();
            $(document).on('change', '.currency-change', function () {
                setCurrency();
            });

            function setCurrency() {
                let currency = $('.currency-change').val();
                let fiatYn = $('.currency-change option:selected').attr('data-fiat');
                if (fiatYn == 0) {
                    $('.set-currency').text(currency);
                } else {
                    $('.set-currency').text('USD');
                }
            }

            $(document).on('click', '.copy-btn', function () {
                var _this = $(this)[0];
                var copyText = $(this).parents('.input-group-append').siblings('input');
                $(copyText).prop('disabled', false);
                copyText.select();
                document.execCommand("copy");
                $(copyText).prop('disabled', true);
                $(this).text('Coppied');
                setTimeout(function () {
                    $(_this).text('');
                    $(_this).html('<i class="fas fa-copy"></i>');
                }, 500)
            });

            $('#image').on('change', function () {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#image_preview_container').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });
        });

        $(document).ready(function () {
            $('.js-example-basic-multiple').select2();
        });

        $(document).on('click', '#generate', function () {
            var form = `<div class="col-md-12">
        <div class="form-group">
            <div class="input-group">
                <input name="field_name[]" class="form-control " type="text" value="" required
                       placeholder="@lang("Field Name")">

                <select name="type[]" class="form-control ">
                    <option value="text">@lang("Input Text")</option>
                    <option value="textarea">@lang("Textarea")</option>
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

        })
        $(document).on('click', '.delete_desc', function () {
            $(this).closest('.input-group').parent().remove();
        });


    </script>
@endpush
