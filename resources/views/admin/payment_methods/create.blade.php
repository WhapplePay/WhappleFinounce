@extends('admin.layouts.app')
@section('title')
    {{ $page_title }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary shadow">
                    <div class="card-body">
                        <form method="post" action="{{route('admin.store.payment.methods')}}"
                              class="needs-validation base-form" novalidate="" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-4 col-6">
                                    <label>@lang('Name')</label>
                                    <input type="text" class="form-control "
                                           name="name" value="{{old('name')}}">
                                    @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4 col-6">
                                    <label>@lang('Border Color')</label>
                                    <input type="color" class="form-control "
                                           name="color" value="{{old('color')}}">
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
                                             src=""
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
                                        <input type="checkbox" name="status" class="custom-switch-checkbox"
                                               id="status"
                                               value="0">
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
                            </div>
                            <button type="submit"
                                    class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3">@lang('Create')
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
    @if ($errors->any())
        @php
            $collection = collect($errors->all());
            $errors = $collection->unique();
        @endphp
        <script>
            "use strict";
            @foreach ($errors as $error)
            Notiflix.Notify.Failure("{{trans($error)}}");
            @endforeach
        </script>
    @endif
@endpush
