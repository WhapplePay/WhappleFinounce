@extends('admin.layouts.app')
@section('title')
    {{$cryptoCode}}  <i class="fas fa-exchange-alt"></i>  {{$fiatCode}} @lang('Feedbacks')
@endsection
@section('content')
    <div class="page-header card card-primary m-0 m-md-4 my-4 m-md-0 p-5 shadow">
        <div class="row justify-content-between">
            <div class="col-md-12">
                <form action="{{route('admin.feedback.list',$adId)}}" method="get">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" name="reviewer" value="{{@request()->reviewer}}"
                                       class="form-control get-trx-id"
                                       placeholder="@lang('Search for reviewer')">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="date" class="form-control" name="datetrx" id="datepicker"/>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <button type="submit" class="btn waves-effect waves-light btn-primary w-100"><i
                                        class="fas fa-search"></i> @lang('Search')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <table class="categories-show-table table table-hover table-striped table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">@lang('SL No.')</th>
                    <th scope="col">@lang('Author')</th>
                    <th scope="col">@lang('Reviewer')</th>
                    <th scope="col">@lang('Review At')</th>
                    <th scope="col">@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($feedbacks as $key => $item)
                    <tr>
                    <tr>
                        <td data-label="@lang('SL No.')">{{++$key}}</td>
                        <td data-label="@lang('Author')">
                            <a href="{{route('admin.user-edit',$item->creator_id )}}">
                                <div class="d-flex no-block align-items-center">
                                    <div class="mr-3"><img
                                            src="{{ getFile(config('location.user.path') . optional($item->creator)->image) }}"
                                            alt="user" class="rounded-circle" width="35" height="35"></div>
                                    <div class="mr-3">
                                        <h5 class="text-dark mb-0 font-16 font-weight-medium">@lang(optional($item->creator)->username)</h5>
                                        <span
                                            class="text-muted font-14">{{optional($item->creator)->email}}</span>
                                    </div>
                                </div>
                            </a>
                        </td>
                        <td data-label="@lang('Reviewer')">
                            <a href="{{route('admin.user-edit',$item->reviewer_id)}}">
                                <div class="d-flex no-block align-items-center">
                                    <div class="mr-3"><img
                                            src="{{ getFile(config('location.user.path') . optional($item->reviewer)->image) }}"
                                            alt="user" class="rounded-circle" width="35" height="35"></div>
                                    <div class="mr-3">
                                        <h5 class="text-dark mb-0 font-16 font-weight-medium">@lang(optional($item->reviewer)->username)</h5>
                                        <span
                                            class="text-muted font-14">{{optional($item->reviewer)->email}}</span>
                                    </div>
                                </div>
                            </a>
                        </td>
                        <td data-label="@lang('Review At')">
                            {{dateTime($item->created_at,'d M, Y h:i A')}}
                        </td>

                        <td data-label="@lang('Action')">
                            <a href="javascript:void(0)" data-target="#details" data-toggle="modal"
                               class="btn btn-sm btn-outline-info details" data-resource="{{$item}}"
                               data-toggle="tooltip" title="Details" data-original-title="Details">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="javascript:void(0)" data-target="#deleteModal" data-toggle="modal"
                               class="btn btn-sm btn-outline-danger deleteFeed"
                               data-route="{{route('admin.feedback.Delete',$item->id)}}"
                               data-toggle="tooltip" title="Delete" data-original-title="Delete">
                                <i class="fa fa-trash"></i>
                            </a>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center text-danger" colspan="10">@lang('No Data Found')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            {{ $feedbacks->links('partials.pagination') }}
        </div>
    </div>

    <!-- Feedback Modal -->
    <div id="details" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="primary-header-modalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title modalTitle" id="primary-header-modalLabel">@lang('Feedback')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label>@lang('Message')</label>
                            <div class="form-group">
                                <textarea class="form-control message" rows="10" cols="10"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Feedback Modal -->
    <div id="deleteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="primary-header-modalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title modalTitle" id="primary-header-modalLabel">@lang('Delete Confirmation')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <p>@lang('Are you really want to delete this feedback?')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">@lang('Close')</button>
                    <form action="" method="post" class="deleteRoute">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-info">@lang('Yes')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        'use script'
        $(document).on('click', '.deleteFeed', function (e) {
            var route = $(this).data('route');
            $('.deleteRoute').attr('action', route)
        });

        $(document).on('click', '.details', function (e) {
            var obj = $(this).data('resource');
            $('.message').text(obj.feedback);
        });
    </script>
@endpush
