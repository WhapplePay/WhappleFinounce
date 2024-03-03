@extends($theme.'layouts.user')
@section('title')
    @lang('Transaction')
@endsection
@section('content')
    <div class="container-fluid">
        <div class="main row">
            <div class="col-12">
                <div class="search-bar">
                    <form action="{{route('user.transaction.search')}}" method="get">
                        <div class="row g-3 align-items-end">
                            <div class="input-box col-lg-3">
                                <div class="form-group mb-2">
                                    <input type="text" name="transaction_id"
                                           value="{{@request()->transaction_id}}"
                                           class="form-control"
                                           placeholder="@lang('Search for Transaction ID')">
                                </div>
                            </div>

                            <div class="input-box col-lg-3">
                                <div class="form-group mb-2">
                                    <input type="text" name="remark" value="{{@request()->remark}}"
                                           class="form-control"
                                           placeholder="@lang('Remark')">
                                </div>
                            </div>


                            <div class="input-box col-lg-3">
                                <div class="form-group mb-2">
                                    <input type="date" class="form-control" name="datetrx" id="datepicker"/>
                                </div>
                            </div>

                            <div class="input-box col-lg-3">
                                <div class="form-group mb-2 h-fill">
                                    <button type="submit" class="btn-custom w-100">
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
                    <th>@lang('SL No.')</th>
                    <th>@lang('Transaction ID')</th>
                    <th>@lang('Amount')</th>
                    <th>@lang('Remarks')</th>
                    <th>@lang('Time')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($transactions as $transaction)
                    <tr>
                        <td data-label="@lang('SL No.')">{{loopIndex($transactions) + $loop->index}}</td>
                        <td data-label="@lang('Transaction ID')">@lang($transaction->trx_id)</td>
                        <td data-label="@lang('Amount')">
                                        <span
                                            class="font-weight-bold text-{{($transaction->trx_type == "+") ? 'success': 'danger'}}">{{($transaction->trx_type == "+") ? '+': '-'}}{{getAmount($transaction->amount,8). ' ' . $transaction->code}}</span>
                        </td>
                        <td data-label="@lang('Remarks')"> @lang($transaction->remarks)</td>
                        <td data-label="@lang('Time')">
                            {{ dateTime($transaction->created_at, 'd M Y h:i A') }}
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
                {{ $transactions->appends($_GET)->links($theme.'partials.pagination') }}
            </ul>
        </nav>
    </div>
@endsection
