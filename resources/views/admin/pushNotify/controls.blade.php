@extends('admin.layouts.app')
@section('title')
    @lang('Push Notification')
@endsection
@section('content')

    <div class="row">
        <div class="col-md-7">
            <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
                <div class="card-body">
                    <form method="post" action="" class="needs-validation base-form">
                        @csrf
                        <div class="row my-3">
                            <div class="form-group col-sm-6 col-12">
                                <label class="font-weight-bold">@lang('Server Key')</label>
                                <input type="text" name="server_key"
                                       value="{{ old('server_key',$control->server_key) }}"
                                       required="required" class="form-control ">
                                @error('server_key')
                                <span class="text-danger">{{ trans($message) }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6 col-12">
                                <label class="font-weight-bold">@lang('Vapid Key')</label>
                                <input type="text" name="vapid_key"
                                       value="{{ old('vapid_key',$control->vapid_key) }}" required="required"
                                       class="form-control ">
                                @error('vapid_key')
                                <span class="text-danger">{{ trans($message) }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6 col-12">
                                <label class="font-weight-bold">@lang('Api Key')</label>
                                <input type="text" name="api_key"
                                       value="{{ old('api_key',$control->api_key) }}"
                                       required="required"
                                       class="form-control ">
                                @error('api_key')
                                <span class="text-danger">{{ trans($message) }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-sm-6 col-12">
                                <label class="font-weight-bold">@lang('Auth Domain')</label>
                                <input type="text" name="auth_domain"
                                       value="{{ old('auth_domain',$control->auth_domain) }}"
                                       required="required"
                                       class="form-control ">
                                @error('auth_domain')
                                <span class="text-danger">{{ trans($message) }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-sm-4 col-12">
                                <label class="font-weight-bold">@lang('Project Id')</label>
                                <input type="text" name="project_id"
                                       value="{{ old('project_id',$control->project_id) }}"
                                       required="required"
                                       class="form-control ">
                                @error('project_id')
                                <span class="text-danger">{{ trans($message) }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-sm-4 col-12">
                                <label class="font-weight-bold">@lang('Storage Bucket')</label>
                                <input type="text" name="storage_bucket"
                                       value="{{ old('storage_bucket',$control->storage_bucket) }}"
                                       required="required"
                                       class="form-control ">
                                @error('storage_bucket')
                                <span class="text-danger">{{ trans($message) }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-sm-4 col-12">
                                <label class="font-weight-bold">@lang('Messaging Sender Id')</label>
                                <input type="text" name="messaging_sender_id"
                                       value="{{ old('messaging_sender_id',$control->messaging_sender_id) }}"
                                       required="required"
                                       class="form-control ">
                                @error('messaging_sender_id')
                                <span class="text-danger">{{ trans($message) }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-sm-4 col-12">
                                <label class="font-weight-bold">@lang('App Id')</label>
                                <input type="text" name="app_id"
                                       value="{{ old('app_id',$control->app_id) }}"
                                       required="required"
                                       class="form-control ">
                                @error('app_id')
                                <span class="text-danger">{{ trans($message) }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-sm-4 col-12">
                                <label class="font-weight-bold">@lang('Measurement Id')</label>
                                <input type="text" name="measurement_id"
                                       value="{{ old('measurement_id',$control->measurement_id) }}"
                                       required="required"
                                       class="form-control ">
                                @error('measurement_id')
                                <span class="text-danger">{{ trans($message) }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-sm-6  col-12">
                                <label class="font-weight-bold">@lang('User Foreground')</label>
                                <div class="custom-switch-btn">
                                    <input type='hidden' value='1' name='user_foreground'>
                                    <input type="checkbox" name="user_foreground" class="custom-switch-checkbox"
                                           id="user_foreground"
                                           value="0" {{($control->user_foreground == 0) ? 'checked' : ''}} >
                                    <label class="custom-switch-checkbox-label" for="user_foreground">
                                        <span class="custom-switch-checkbox-inner"></span>
                                        <span class="custom-switch-checkbox-switch"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group col-sm-6  col-12">
                                <label class="font-weight-bold">@lang('User Background')</label>
                                <div class="custom-switch-btn">
                                    <input type='hidden' value='1' name='user_background'>
                                    <input type="checkbox" name="user_background" class="custom-switch-checkbox"
                                           id="user_background"
                                           value="0" {{($control->user_background == 0) ? 'checked' : ''}} >
                                    <label class="custom-switch-checkbox-label" for="user_background">
                                        <span class="custom-switch-checkbox-inner"></span>
                                        <span class="custom-switch-checkbox-switch"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group col-sm-6  col-12">
                                <label class="font-weight-bold">@lang('Admin Foreground')</label>
                                <div class="custom-switch-btn">
                                    <input type='hidden' value='1' name='admin_foreground'>
                                    <input type="checkbox" name="admin_foreground" class="custom-switch-checkbox"
                                           id="admin_foreground"
                                           value="0" {{($control->admin_foreground == 0) ? 'checked' : ''}} >
                                    <label class="custom-switch-checkbox-label" for="admin_foreground">
                                        <span class="custom-switch-checkbox-inner"></span>
                                        <span class="custom-switch-checkbox-switch"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group col-sm-6  col-12">
                                <label class="font-weight-bold">@lang('Admin Background')</label>
                                <div class="custom-switch-btn">
                                    <input type='hidden' value='1' name='admin_background'>
                                    <input type="checkbox" name="admin_background" class="custom-switch-checkbox"
                                           id="admin_background"
                                           value="0" {{($control->admin_background == 0) ? 'checked' : ''}} >
                                    <label class="custom-switch-checkbox-label" for="admin_background">
                                        <span class="custom-switch-checkbox-inner"></span>
                                        <span class="custom-switch-checkbox-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <button type="submit"
                                class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3"><span><i
                                    class="fas fa-save pr-2"></i> @lang('Save Changes')</span></button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between mb-3">
                        <div class="col-md-6">
                            <h4 class="card-title  font-weight-bold">@lang('Instructions')</h4>
                        </div>

                        <div class="col-md-6">
                            <a href="https://www.youtube.com/watch?v=F_l69SMj6XU" target="_blank"
                               class="btn btn-primary btn-sm mb-2 text-white float-right" type="button">
                                <span class="btn-label"><i class="fab fa-youtube"></i></span>
                                @lang('How to set up it?')
                            </a>
                        </div>
                    </div>

                    @lang('Push notification provides realtime communication between servers, apps and devices.
                    When something happens in your system, it can update web-pages, apps and devices.
                    When an event happens on an app, the app can notify all other apps and your system
                    <br><br>
                    Get your free API keys')
                    <a href="https://console.firebase.google.com/" target="_blank">@lang('Create an account')
                        <i class="fas fa-external-link-alt"></i></a>
                    @lang(', then create a Firebase Project, then create a web app in created Project
                    Go to web app configuration details to get Vapid key, Api key, Auth domain, Project id, Storage bucket, Messaging sender id, App id, Measurement id.')
                </div>
            </div>
        </div>
    </div>
@endsection

