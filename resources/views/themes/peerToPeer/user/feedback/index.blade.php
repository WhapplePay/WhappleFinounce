@extends($theme.'layouts.user')
@section('title')
    @lang('Feedback')
@endsection
@section('content')
    <div class="container-fluid">
        <div class="table-parent table-responsive mt-5">
            <table class="table table-striped" id="service-table">
                <thead>
                <tr>
                    <th>@lang('SL No.')</th>
                    <th>@lang('Given By')</th>
                    <th>@lang('Feedback')</th>
                    <th>@lang('Given At')</th>
                    <th>@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($ads as $key => $item)
                    <tr>
                        <td data-label="@lang('SL No.')">{{++$key}}</td>
                        <td data-label="@lang('Seller')">
                            <a href="{{route('user.profile.page',$item->reviewer_id)}}">
                                <div class="d-lg-flex d-block align-items-center">
                                    <div class="me-3"><img
                                            src="{{getFile(config('location.user.path').optional($item->reviewer)->image) }}"
                                            alt="user" class="rounded-circle" width="45"
                                            height="45"></div>
                                    <div class="">
                                        <h6 class="text-white mb-0 text-lowercase">@lang(optional($item->reviewer)->username)</h6>
                                        <span
                                            class="text-muted font-14 text-lowercase">{{optional($item->reviewer)->email}}</span>
                                    </div>
                                </div>
                            </a>
                        </td>
                        <td data-label="@lang('Feedback')">
                            @if($item->position == 'like')
                                <i class="far fa-thumbs-up"></i>
                            @elseif($item->position == 'dislike')
                                <i class="far fa-thumbs-down"></i>
                            @endif
                        </td>
                        <td data-label="@lang('Given At')"><span>{{dateTime($item->created_at,'d M, Y h:i A')}}</span>
                        </td>
                        <td data-label="@lang('Action')" class="action">
                            <button class="btn-custom details" data-resource="{{$item}}"
                                    data-bs-target="#detailsModal"
                                    data-bs-toggle="modal">
                                @lang('Details')
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%">
                            <div class="no-data-message">
                                <span class="icon-wrapper">
                                    <span class="file-icon">
                                        <i class="fas fa-file-times"></i>
                                    </span>
                                </span>
                                <p class="message">@lang('No data found')</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                {{ $ads->appends($_GET)->links($theme.'partials.pagination') }}
            </ul>
        </nav>
    </div>

    <!--Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">@lang('Feedback')</h5>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>
                <form>
                    @csrf
                    <div class="modal-body">
                        <div class="input-box">
                            <label
                                for="exampleFormControlTextarea1"
                                class="form-label">@lang('message')</label>
                            <textarea
                                class="form-control color-adjust textMessage"
                                id="exampleFormControlTextarea1"
                                rows="5"
                                name="feedback"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-custom btn2" data-bs-dismiss="modal">@lang('Close')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        'use script'
        $(document).on('click', '.details', function (e) {
            var obj = $(this).data('resource');
            $('.textMessage').val(obj.feedback);
        });
    </script>
@endpush
