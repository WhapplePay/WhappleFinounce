@extends($theme.'layouts.user')
@section('title',trans($title))
@section('content')
    <div class="container-fluid">
        <div class="main row">
            <div class="col-12">
                <div class="col-md-12">
                    <div class="search-bar">
                        <form action="{{ route('user.deposit.history') }}" method="get">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <div class="form-group input-box mb-2">
                                        <input type="text" name="trx" value="{{@request()->trx}}"
                                               class="form-control"
                                               placeholder="@lang('Trx Number')">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group input-box mb-2">
                                        <input type="date" class="form-control" name="date_time"
                                               id="datepicker"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-2 h-fill">
                                        <button type="submit" class="btn-custom">
                                            <i class="fas fa-search"></i> @lang('Search')</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="table-parent table-responsive">
                <table class="table table-striped" id="service-table">
                    <thead>
                    <tr>
                        <th scope="col">@lang('Date')</th>
                        <th scope="col">@lang('Trx Number')</th>
                        <th scope="col">@lang('Amount')</th>
                        <th scope="col">@lang('Charge')</th>
                        <th scope="col">@lang('Status')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($depositLog as $item)
                        <tr>
                            <td data-label="#@lang('Date')">{{ dateTime($item->created_at,'d M,Y H:i') }}</td>
                            <td data-label="#@lang('Trx Number')">{{ $item->trx }}</td>
                            <td data-label="@lang('Amount')">
                                <strong>{{ getAmount($item->amount,8) }} {{ optional($item->crypto)->code }}</strong>
                            </td>
                            <td data-label="@lang('Charge')">
                                <strong
                                    class="text-danger">{{ getAmount($item->charge,8)}} {{ optional($item->crypto)->code }}</strong>
                            </td>

                            <td data-label="@lang('Status')">
                                @if($item->status == 1)
                                    <span class="badge bg-success">@lang('Success')</span>
                                @endif
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
                    {{ $depositLog->appends($_GET)->links($theme.'partials.pagination') }}
                </ul>
            </nav>
        </div>
    </div>

    <div id="infoModal" class="modal fade" tabindex="-1" data-backdrop="static" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content form-block">

                <div class="modal-header">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <button type="button" class="close closeModal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group ">
                        <li class="list-group-item bg-transparent">@lang('Transactions') : <span class="trx"></span>
                        </li>
                        <li class="list-group-item bg-transparent">@lang('Admin Feedback') : <span
                                class="feedback"></span></li>
                    </ul>
                    <div class="payout-detail">

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light closeModal" data-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

    <script>
        "use strict";

        $(document).ready(function () {
            $('.infoButton').on('click', function () {
                var infoModal = $('#infoModal');
                infoModal.find('.trx').text($(this).data('trx_id'));
                infoModal.find('.feedback').text($(this).data('feedback'));
                var list = [];
                var information = Object.entries($(this).data('information'));

                var ImgPath = "{{asset(config('location.withdrawLog.path'))}}/";
                var result = ``;
                for (var i = 0; i < information.length; i++) {
                    if (information[i][1].type == 'file') {
                        result += `<li class="list-group-item bg-transparent">
                                            <span class="font-weight-bold "> ${information[i][0].replaceAll('_', " ")} </span> : <img class="w-100"src="${ImgPath}/${information[i][1].field_name}" alt="..." class="w-100">
                                        </li>`;
                    } else {
                        result += `<li class="list-group-item bg-transparent">
                                            <span class="font-weight-bold "> ${information[i][0].replaceAll('_', " ")} </span> : <span class="font-weight-bold ml-3">${information[i][1].field_name}</span>
                                        </li>`;
                    }
                }

                if (result) {
                    infoModal.find('.payout-detail').html(`<br><strong class="my-3">@lang('Payment Information')</strong>  ${result}`);
                } else {
                    infoModal.find('.payout-detail').html(`${result}`);
                }
                infoModal.modal('show');
            });


            $('.closeModal').on('click', function (e) {
                $("#infoModal").modal("hide");
            });
        });

    </script>
@endpush
