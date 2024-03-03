@extends($theme.'layouts.user')
@section('title',trans('Profile Settings'))
@section('content')
    <!-- main -->
    <div class="container-fluid">
        <div class="main row">
            <!-- edit profile section -->
            <section class="edit-profile-section">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('user.updateInformation')}}" class="mt-0" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="sidebar-wrapper">
                                <div class="cover">
                                    <div class="img">
                                        <img id="cover" src="{{getFile(config('location.user.path').auth()->user()->cover_image)}}" alt="..." class="img-fluid"/>
                                        <button class="upload-img">
                                            <i class="fal fa-camera" aria-hidden="true"></i>
                                            <input
                                                class="form-control"
                                                name="coverImage"
                                                accept="image/*"
                                                type="file"
                                                onchange="previewImage('cover')"
                                            />
                                        </button>
                                    </div>
                                </div>
                                <div class="profile">
                                    <div class="img">
                                        <img id="profile"
                                             src="{{getFile(config('location.user.path').auth()->user()->image)}}"
                                             alt="..."
                                             class="img-fluid"/>
                                        <button class="upload-img">
                                            <i class="fal fa-camera" aria-hidden="true"></i>
                                            <input
                                                class="form-control"
                                                name="image"
                                                accept="image/*"
                                                type="file"
                                                onchange="previewImage('profile')"
                                            />
                                        </button>
                                    </div>
                                    <div>
                                        <h5 class="name">@lang(auth()->user()->fullname)</h5>
                                        <span>{{auth()->user()->email}}</span>
                                    </div>
                                </div>
                            </div>

                            <h4 class="mb-3 mt-5">@lang('Basic Info')</h4>
                            <div class="row g-4">
                                <div class="input-box col-md-6">
                                    <label for="">@lang('First Name')</label>
                                    <input class="form-control" type="text" name="firstname"
                                           value="{{old('firstname')?: $user->firstname }}"
                                           placeholder="@lang('First Name')"/>
                                    @if($errors->has('firstname'))
                                        <div
                                            class="error text-danger">@lang($errors->first('firstname')) </div>
                                    @endif
                                </div>
                                <div class="input-box col-md-6">
                                    <label for="">@lang('Last Name')</label>
                                    <input class="form-control" type="text" name="lastname"
                                           value="{{old('lastname')?: $user->lastname }}"
                                           placeholder="@lang('Last Name')"/>
                                    @if($errors->has('lastname'))
                                        <div
                                            class="error text-danger">@lang($errors->first('lastname')) </div>
                                    @endif
                                </div>
                                <div class="input-box col-md-6">
                                    <label for="">@lang('Username')</label>
                                    <input class="form-control" type="text" name="username"
                                           value="{{old('username')?: $user->username }}"
                                           placeholder="@lang('Username')"/>
                                    @if($errors->has('username'))
                                        <div
                                            class="error text-danger">@lang($errors->first('username')) </div>
                                    @endif
                                </div>
                                <div class="input-box col-md-6">
                                    <label for="">@lang('Email Address')</label>
                                    <input class="form-control" type="email" value="{{ $user->email }}" readonly/>
                                    @if($errors->has('email'))
                                        <div
                                            class="error text-danger">@lang($errors->first('email')) </div>
                                    @endif
                                </div>
                                <div class="input-box col-md-6">
                                    <label for="">@lang('Phone number')</label>
                                    <input class="form-control" type="text" value="{{$user->phone}}" readonly/>
                                    @if($errors->has('phone'))
                                        <div
                                            class="error text-danger">@lang($errors->first('phone')) </div>
                                    @endif
                                </div>
                                <div class="input-box col-md-6">
                                    <label for="">@lang('Preferred language')</label>
                                    <select name="language_id" id="language_id" class="form-select"
                                            aria-label="Default select example">
                                        <option value="" disabled>@lang('Select Language')</option>
                                        @foreach($languages as $la)
                                            <option value="{{$la->id}}"

                                                {{ old('language_id', $user->language_id) == $la->id ? 'selected' : '' }}>@lang($la->name)</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('language_id'))
                                        <div
                                            class="error text-danger">@lang($errors->first('language_id')) </div>
                                    @endif
                                </div>
                                <div class="input-box col-12">
                                    <label for="">@lang('Address')</label>
                                    <textarea class="form-control" name="address" cols="30" rows="3"
                                              placeholder="@lang('Address')">@lang($user->address)</textarea>
                                    @if($errors->has('address'))
                                        <div
                                            class="error text-danger">@lang($errors->first('address')) </div>
                                    @endif
                                </div>
                                <div class="input-box col-12">
                                    <button type="submit" class="btn-custom">@lang('Submit changes')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
