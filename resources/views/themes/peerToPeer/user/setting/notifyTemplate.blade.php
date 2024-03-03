@extends($theme.'user.setting.index')
@section('dynamic')
    <div class="col-lg-4">
        <div class="side-bar">
            <!-- edit profile section -->
            <div class="identity-confirmation">
                <form role="form" method="POST" action="{{route('user.update.setting.notify')}}"
                      enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="row g-4">
                        <div class="table-parent table-responsive mt-5">
                            <table class="table table-striped" id="service-table">
                                <thead>
                                <tr>
                                    <th colspan="2">@lang('Notification List')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($templates as $key => $item)
                                    <tr>
                                        <td data-label="@lang('Notification List')">
                                            {{$item->name}}
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="flex{{$key}}"
                                                       name="access[]"
                                                       value="{{$item->template_key}}"
                                                       @if(in_array($item->template_key, auth()->user()->notify_active_template??[])) checked
                                                       @endif
                                                       role="switch">
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn-custom">@lang('Save Changes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
