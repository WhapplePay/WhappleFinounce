@extends($theme.'layouts.user')
@section('title',trans('Your Wallet'))
@section('content')

    <div class="container-fluid">
        <div class="main row">
            <div class="col-12">
                <h4>@lang('My Wallets')</h4>
            </div>

            @forelse($wallets as $key => $item)
                <div class="col-xl-3 col-sm-6">
                    <div class="custom-card mb-4">
                        <div class="card-content {{($cryptId == $item->crypto_currency_id)?'border-active':''}}">
                            <div class="card-top">
                                <div class="card_header">{{optional($item->crypto)->name}}</div>
                                <div class="dropdown">
                                    <a class="" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fal fa-ellipsis-v"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        {{-- <li>
                                            <a class="dropdown-item text-capitalize deposit btnDeposit"
                                               href="javascript:void(0)"
                                               data-resource="{{$item}}"
                                               data-bs-target="#depositModal"
                                               data-bs-toggle="modal">@lang('generate address')</a>
                                        </li> --}}

                                        {{-- <li>
                                            <a class="dropdown-item text-capitalize withdraw btnWithdraw"
                                               href="javascript:void(0)"
                                               title="withdraw"
                                               data-resource="{{$item}}"
                                               data-bs-target="#withdrawModal"
                                               data-bs-toggle="modal">@lang("withdraw")</a>
                                        </li> --}}

                                        <li>
                                            <a class="dropdown-item text-capitalize"
                                               href="{{route('user.transaction',optional($item->crypto)->code)}}"
                                               title="withdraw">@lang("transaction")</a>
                                        </li>
                                        {{-- <li>
                                            <a class="dropdown-item text-capitalize"
                                               href="{{route('user.wallet.list',$item->crypto_currency_id)}}"
                                               title="wallet address">@lang("wallet address")</a>
                                        </li> --}}

                                    </ul>
                                </div>

                            </div>
                            <div class="card-bottom w-75">
                                <span
                                    class="flex-wrap ">{{rtrim(sprintf('%.8f',floatval($item->balance)),'0') }} {{optional($item->crypto)->code}}</span>
                            </div>
                            <div class="coin-thum">
                                <img
                                    src="{{getFile(config('location.currency.path').optional($item->crypto)->image)}}"
                                    alt="">
                            </div>
                        </div>
                    </div>
                </div>
            @empty
            @endforelse
        </div>
        @if($cryptId != null)
            <div class="table-parent table-responsive" id="wallet-app" v-cloak>
                <table class="table table-striped" id="service-table">
                    <thead>
                    <tr>
                        <th>@lang('SL No.')</th>
                        <th>@lang('Generate At')</th>
                        <th>@lang('Currency')</th>
                        <th>@lang('Address')</th>
                        <th>@lang('Action')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($cryptoWallets as $key => $item)
                        <tr>
                            <td data-label="@lang('SL No.')">{{++$key}}</td>
                            <td data-label="@lang('Generate At')">{{$item->created_at}}</td>
                            <td data-label="@lang('Currency')">{{optional($item->crypto)->code}}</td>
                            <td data-label="@lang('Address')">{{$item->wallet_address}}</td>
                            <td data-label="@lang('Action')" class="action">
                                <a href="javascript:void(0)"
                                   class="btn-custom p-6"
                                   @click="copyAddress('{{$item->wallet_address}}')">@lang('copy')</a>
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
                    {{ $cryptoWallets->appends($_GET)->links($theme.'partials.pagination') }}
                </ul>
            </nav>
        @endif
    </div>

@endsection
@push('loadModal')
    <!-- Modal -->
    <div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel"></h5>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>

                <form action="{{route('user.payout.moneyRequest')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-4">
                            <input type="hidden" class="currencyId" name="currencyId" value="">
                            <p class="mb-0 withdrawCharge text-danger"></p>
                            <label>@lang('Network')</label>
                            <div class="input-box col-12 mt-1">
                                <input class="form-control" type="text" name="network"
                                       placeholder="@lang('Network')"/>
                                @if($errors->has('network'))
                                    <div
                                        class="error text-danger">@lang($errors->first('network')) </div>
                                @endif
                            </div>
                            <label>@lang('Wallet Address') <sup class="text-danger">*</sup></label>
                            <div class="input-box col-12 mt-1">
                                <input class="form-control" type="text" name="walletAddress"
                                       placeholder="@lang('Wallet Address')"/>
                                @if($errors->has('walletAddress'))
                                    <div
                                        class="error text-danger">@lang($errors->first('walletAddress')) </div>
                                @endif
                            </div>
                            <label>@lang('Withdraw Amount') <sup class="text-danger">*</sup></label>
                            <div class="input-box col-12 mt-1">
                                <input class="form-control" type="text" name="withdrawAmount"
                                       placeholder="@lang('Withdraw Amount')"/>
                                @if($errors->has('withdrawAmount'))
                                    <div
                                        class="error text-danger">@lang($errors->first('withdrawAmount')) </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-custom btn2" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn-custom">@lang('Request Withdraw')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="depositModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="depositModalLabel"></h5>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>

                <form action="{{route('user.wallet.generate')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-4">
                            <input type="hidden" class="currencyId" name="currencyId" value="">
                            <p class="mb-0 depositCharge text-danger"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-custom btn2" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn-custom btn-generate">@lang('Generate New Address')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush
@push('script')
    @if($errors->any())
        <script>
            'use strict';
            @foreach ($errors->all() as $error)
            Notiflix.Notify.Failure(`{{trans($error)}}`);
            @endforeach
        </script>
    @endif
    <script>
        "use script"
        $(document).on('click', '.btnWithdraw', function () {
            var obj = $(this).data('resource');

            if (obj.crypto.withdraw_type == 0) {
                $('.withdrawCharge').text(`Withdraw Charge ${obj.crypto.withdraw_charge}%`);
            } else {
                $('.withdrawCharge').text(`Withdraw Charge ${obj.crypto.withdraw_charge} ${obj.crypto.code}`);
            }
            $('.currencyId').val(obj.crypto_currency_id);
            $('#editModalLabel').text(`Make ${obj.crypto.code} Withdraw`);
        });

        $(document).on('click', '.btnDeposit', function () {
            var obj = $(this).data('resource');
            if (obj.crypto.deposit_type == 0) {
                $('.depositCharge').text(`Deposit Charge ${obj.crypto.deposit_charge}%`);
            } else {
                $('.depositCharge').text(`Deposit Charge ${obj.crypto.deposit_charge} ${obj.crypto.code}`);
            }
            $('.currencyId').val(obj.crypto_currency_id);
            $('.btn-generate').text(`Generate New ${obj.crypto.code} Address`);
            $('#depositModalLabel').text(`Make ${obj.crypto.code} Deposit`);
        });

        var newApp = new Vue({
            el: "#wallet-app",
            data: {},
            mounted() {
            },
            methods: {
                copyAddress(code) {
                    let text = code;
                    navigator.clipboard.writeText(text);
                    Notiflix.Notify.Success(`Copied: ${text}`);
                },
            },
        })
    </script>
@endpush
