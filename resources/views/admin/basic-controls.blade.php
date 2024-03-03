@extends('admin.layouts.app')
@section('title')
    @lang('Basic Controls')
@endsection
@section('content')

    <div class="bd-callout bd-callout-warning m-0 m-md-4 my-4 m-md-0 ">
        <i class="fas fa-info-circle mr-2"></i> @lang("If you get 500(server error) for some reason, please turn on <b>Debug Mode</b> and try again. Then you can see what was missing in your system.")
    </div>
    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <form method="post" action="" class="needs-validation base-form">
                @csrf
                <div class="row">
                    <div class="form-group col-md-3">
                        <label class="text-dark">@lang('Site Title')</label>
                        <input type="text" name="site_title"
                               value="{{ old('site_title') ?? $control->site_title ?? 'Site Title' }}"
                               class="form-control ">

                        @error('site_title')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror

                    </div>
                    <div class="form-group col-md-3">
                        <label class="text-dark">@lang('Base Color')</label>
                        <input type="color" name="base_color"
                               value="{{ old('base_color') ?? $control->base_color ?? '#d09f06' }}"
                               required="required" class="form-control ">
                        @error('base_color')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label class="text-dark">@lang('Secondary Color')</label>
                        <input type="color" name="secondary_color"
                               value="{{ old('secondary_color') ?? $control->secondary_color ?? '#f0b90b' }}"
                               required="required" class="form-control ">
                        @error('secondary_color')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="form-group col-md-3">
                        <label class="text-dark">@lang('TimeZone')</label>
                        <select class="form-control" id="exampleFormControlSelect1" name="time_zone">
                            <option hidden>{{ old('time_zone', $control->time_zone)?? 'Select Time Zone' }}</option>
                            @foreach ($control->time_zone_all as $time_zone_local)
                                <option value="{{ $time_zone_local }}">@lang($time_zone_local)</option>
                            @endforeach
                        </select>

                        @error('time_zone')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="form-group col-sm-3 col-12">
                        <label class="text-dark">@lang('Fraction number')</label>
                        <input type="text" name="fraction_number"
                               value="{{ old('fraction_number') ?? $control->fraction_number ?? '2' }}"
                               required="required" class="form-control ">
                        @error('fraction_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-sm-3 col-12">
                        <label class="text-dark">@lang('Paginate Per Page')</label>
                        <input type="text" name="paginate" value="{{ old('paginate') ?? $control->paginate ?? '2' }}"
                               required="required" class="form-control ">
                        @error('paginate')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-sm-3 col-12">
                        <label class="text-dark">@lang('Trade Charge')</label>
                        <div class="input-group">
                            <input type="number" step="0.001" name="tradeCharge"
                                   value="{{ old('tradeCharge') ?? $control->tradeCharge ?? '2' }}"
                                   required="required" class="form-control">
                            <div class="input-group-prepend">
                                <span class="form-control">%</span>
                            </div>
                        </div>
                        @error('tradeCharge')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-sm-3 col-12">
                        <label class="text-dark">@lang('Incomplete Trade Limit For Ad')</label>
                        <div class="form-group">
                            <input type="number" name="incomplete_limit"
                                   value="{{ old('incomplete_limit') ?? $control->incomplete_limit ?? '2' }}"
                                   required="required" class="form-control">
                        </div>
                        @error('incompleteLimit')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-sm-3 col-12">
                        <label class="text-dark">@lang('Trade reported extra time')</label>
                        <div class="input-group">
                            <input type="number" name="trade_extra_time"
                                   value="{{ old('trade_extra_time') ?? $control->trade_extra_time ?? '60' }}"
                                   required="required" class="form-control">
                            <div class="input-group-prepend">
                                <span class="form-control">@lang('Minutes')</span>
                            </div>
                        </div>
                        @error('trade_extra_time')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-sm-6 col-md-4 col-lg-3 ">
                        <label class="text-dark">@lang('Force SSL')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='force_ssl'>
                            <input type="checkbox" name="force_ssl" class="custom-switch-checkbox"
                                   id="force_ssl"
                                   value="0" {{($control->force_ssl == 0) ? 'checked' : ''}} >
                            <label class="custom-switch-checkbox-label" for="force_ssl">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group col-sm-6 col-md-4 col-lg-3 ">
                        <label class="text-dark">@lang('Strong Password')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='strong_password'>
                            <input type="checkbox" name="strong_password" class="custom-switch-checkbox"
                                   id="strong_password"
                                   value="0" {{($control->strong_password == 0) ? 'checked' : ''}} >
                            <label class="custom-switch-checkbox-label" for="strong_password">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group col-sm-6 col-md-4 col-lg-3 ">
                        <label class="text-dark">@lang('Registration')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='registration'>
                            <input type="checkbox" name="registration" class="custom-switch-checkbox"
                                   id="registration"
                                   value="0" {{($control->registration == 0) ? 'checked' : ''}} >
                            <label class="custom-switch-checkbox-label" for="registration">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>


                    <div class="form-group col-lg-3 col-md-6">
                        <label class="text-dark">@lang('Cron Set Up Pop Up')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='cron_set_up_pop_up'>
                            <input type="checkbox" name="cron_set_up_pop_up" class="custom-switch-checkbox"
                                   id="cron_set_up_pop_up"
                                   value="0" <?php if ($control->is_active_cron_notification == 0):echo 'checked'; endif ?> >
                            <label class="custom-switch-checkbox-label" for="cron_set_up_pop_up">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group col-sm-3 ">
                        <label class="text-dark">@lang('Debug Mode')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='error_log'>
                            <input type="checkbox" name="error_log" class="custom-switch-checkbox"
                                   id="error_log"
                                   value="0" <?php if ($control->error_log == 0):echo 'checked'; endif ?> >
                            <label class="custom-switch-checkbox-label" for="error_log">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group col-sm-3 ">
                        <label class="text-dark">@lang('Advertises Rate Update')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='ad_rate_update'>
                            <input type="checkbox" name="ad_rate_update" class="custom-switch-checkbox"
                                   id="ad_rate_update"
                                   value="0" <?php if ($control->ad_rate_update == 0):echo 'checked'; endif ?> >
                            <label class="custom-switch-checkbox-label" for="ad_rate_update">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>

                </div>


                <button type="submit" class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3"><span><i
                            class="fas fa-save pr-2"></i> @lang('Save Changes')</span></button>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        "use strict";
        $(document).ready(function () {
            $('select').select2({
                selectOnClose: true
            });
        });
    </script>
@endpush
