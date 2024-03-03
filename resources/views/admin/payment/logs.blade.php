@extends('admin.layouts.app')
@section('title')
    @lang($page_title)
@endsection
@section('content')
    <div class="page-header card card-primary m-0 m-md-4 my-4 m-md-0 p-5 shadow">
        <form action="{{ route('admin.payment.search') }}" method="get">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" name="name" value="{{@request()->name}}" class="form-control"
                               placeholder="@lang('User Information')">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" name="trx" value="{{@request()->trx}}" class="form-control"
                               placeholder="@lang('Trx Number')">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <input type="date" class="form-control" name="date_time" id="datepicker"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <button type="submit" class="btn waves-effect waves-light btn-primary"><i
                                class="fas fa-search"></i> @lang('Search')</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="categories-show-table table table-hover table-striped table-bordered">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">@lang('Date')</th>
                        <th scope="col">@lang('Trx Number')</th>
                        <th scope="col">@lang('User')</th>
                        <th scope="col">@lang('Amount + Charge')</th>
                        <th scope="col">@lang('Status')</th>
                        <th scope="col">@lang('Action')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($funds as $key => $fund)
                        <tr>
                            <td data-label="@lang('Date')"> {{ dateTime($fund->created_at,'d M,Y H:i') }}</td>
                            <td data-label="@lang('Trx Number')"
                                class="font-weight-bold text-uppercase">{{ $fund->trx }}</td>
                            <td data-label="@lang('Username')">
                                <a href="{{route('admin.user-edit',$fund->user_id )}}">
                                    <div class="d-flex no-block align-items-center">
                                        <div class="mr-3"><img
                                                src="{{ getFile(config('location.user.path') . optional($fund->user)->image) }}"
                                                alt="user" class="rounded-circle" width="35" height="35"></div>
                                        <div class="mr-3">
                                            <h5 class="text-dark mb-0 font-16 font-weight-medium">@lang(optional($fund->user)->username)</h5>
                                            <span
                                                class="text-muted font-14">{{optional($fund->user)->email}}</span>
                                        </div>
                                    </div>
                                </a>
                            </td>
                            <td data-label="@lang('Amount + Charge')"
                                class="font-weight-bold"
                                title="amount">{{ optional($fund->crypto)->code }} {{ getAmount($fund->amount,8) }}
                                + <span class="text-danger"
                                        title="@lang('charge')">{{ getAmount($fund->charge,8)}} {{ optional($fund->crypto)->code }}</span>
                                <br>
                                <strong title="@lang('Amount with charge')">
                                    {{ getAmount($fund->amount + $fund->charge, 8) }} {{ __(optional($fund->crypto)->code) }}
                                </strong>
                            </td>
                            <td data-label="@lang('Status')">
                                @if($fund->status == 1)
                                    <span class="badge badge-success">@lang('Success')</span>
                                @endif
                            </td>
                            <td data-label="@lang('Action')">
                                <button class="btn btn-sm btn-outline-primary details" data-resource="{{$fund}}"
                                        data-target="#detailsModal"
                                        data-toggle="modal">
                                    @lang('Details')</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center text-dark" colspan="100%">
                                @lang('No Data Found')
                            </td>
                        </tr>

                    @endforelse
                    </tbody>
                </table>
                {{ $funds->appends($_GET)->links('partials.pagination') }}
            </div>
        </div>
    </div>

    <!-- Modal for Edit button -->
    <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="myModalLabel">@lang('Deposit Information')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>

                <form>
                    <div class="modal-body">
                        <ul class="rate">
                            <li>@lang('Date:') <span class="caption date"></span></li>
                            <li class="my-2">@lang('Wallet Address:') <span class="caption walletAd"></span>
                            </li>
                            <li>@lang('Transaction Number:') <span class="caption trx"></span></li>
                            <li class="my-2">@lang('Username:') <span class="caption text-info username">2</span></li>
                            <li>@lang('Amount:') <span class="caption amount"></span></li>
                            <li class="my-2">@lang('Charge:') <span class="caption text-danger charge"></span></li>
                            <li class="my-2">@lang('Payable:') <span class="caption text-success payable"></span></li>
                            <li class="mb-4">@lang('Status:') <span class="caption status"></span></li>
                        </ul>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script>
        "use strict";
        $(document).ready(function () {
            $('select[name=status]').select2({
                selectOnClose: true
            });
        });
        $(document).on("click", '.details', function (e) {
            var obj = $(this).data('resource');
            $('.date').text(moment(obj.created_at).format('LLL'));
            $('.walletAd').text(obj.wallet_address);
            $('.trx').text(obj.trx);
            $('.username').text(obj.user.username);
            $('.amount').text(`${obj.amount} ${obj.crypto.code}`);
            $('.charge').text(`${obj.charge} ${obj.crypto.code}`);
            $('.payable').text(`${obj.final_amount} ${obj.crypto.code}`);

            if (obj.status == 1) {
                $('.status').addClass('badge badge-success').text('Success');
            }
            console.log(obj);
        });
    </script>
@endpush
