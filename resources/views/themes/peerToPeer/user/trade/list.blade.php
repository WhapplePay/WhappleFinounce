@extends($theme.'layouts.user')
@section('title')
    @lang('Trade')
@endsection
@section('content')
    <div class="container-fluid">
        <div class="main row">
            <div class="col-12">
                <div class="search-bar">
                    <form action="{{route('user.trade.list')}}" method="get">
                        <div class="row g-3 align-items-end">
                            <div class="input-box col-lg-3">
                                <label for="">@lang('Trade Number')</label>
                                <div class="form-group">
                                    <input type="text" name="tradeNumber"
                                           value="{{@request()->tradeNumber}}"
                                           class="form-control" placeholder="@lang('Trade Number')">
                                </div>
                            </div>
                            <div class="input-box col-lg-3">
                                <label for="">@lang('Username')</label>
                                <div class="form-group">
                                    <input type="text" name="username"
                                           value="{{@request()->username}}"
                                           class="form-control" placeholder="@lang('Username')">
                                </div>
                            </div>
                            <div class="input-box col-lg-3">
                                <label for="">@lang('Status')</label>
                                <select class="form-select" aria-label="Default select example" name="status">
                                    <option selected disabled>@lang('Select Status')</option>
                                    <option
                                        value="0" {{@request()->status == '0'?'selected':''}}>@lang('Pending')</option>
                                    <option
                                        value="1" {{@request()->status == '1'?'selected':''}}>@lang('Buyer paid')</option>
                                    <option
                                        value="3" {{@request()->status == '3'?'selected':''}}>@lang('Cancel')</option>
                                    <option
                                        value="4" {{@request()->status == '4'?'selected':''}}>@lang('Complete')</option>
                                    <option
                                        value="5" {{@request()->status == '5'?'selected':''}}>@lang('Reported')</option>
                                </select>
                            </div>

                            <div class="input-box col-lg-3">
                                <div class="form-group">
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
                    <th>@lang('Trade Number')</th>
                    <th>@lang('With')</th>
                    <th>@lang('Type')</th>
                    <th>@lang('Currency')</th>
                    <th>@lang('Payment Method')</th>
                    <th>@lang('Rate')</th>
                    <th>@lang('Crypto Amount')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Requested On')</th>
                    <th>@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($trades as $key => $item)
                    <tr>
                        <td data-label="@lang('SL No.')">{{$item->trade_number}}</td>
                        <td data-label="@lang('With')">
                            @if($item->owner_id != auth()->user()->id)
                                <a href="{{route('user.profile.page',optional($item->owner)->id)}}">
                                    <div class="d-lg-flex d-block align-items-center">
                                        <div class="me-3"><img
                                                src="{{getFile(config('location.user.path').optional($item->owner)->image) }}"
                                                alt="user" class="rounded-circle" width="45"
                                                height="45">
                                            <i class="tb-online position-absolute fa fa-circle text-{{(optional($item->owner)->lastSeen == true) ?trans('success'):trans('danger') }}"
                                               title="{{(optional($item->owner)->lastSeen == true) ?trans('Online'):trans('Away') }}"></i>
                                        </div>
                                        <div class="">
                                            <h6 class="text-white mb-0 text-lowercase">@lang(optional($item->owner)->username)</h6>
                                            <span
                                                class="text-muted font-10">{{optional($item->owner)->total_trade}} @lang('Trades') |</span>
                                            <span
                                                class="text-muted font-10">@lang('Completion') {{number_format(optional($item->owner)->completed_trade*100/optional($item->owner)->total_trade,2)}}%</span>
                                        </div>
                                    </div>
                                </a>
                            @else
                                <a href="{{route('user.profile.page',optional($item->sender)->id)}}">
                                    <div class="d-lg-flex d-block align-items-center">
                                        <div class="me-3"><img
                                                src="{{getFile(config('location.user.path').optional($item->sender)->image) }}"
                                                alt="user" class="rounded-circle" width="45"
                                                height="45">
                                            <i class="tb-online position-absolute fa fa-circle text-{{(optional($item->sender)->lastSeen == true) ?trans('success'):trans('danger') }}"
                                               title="{{(optional($item->sender)->lastSeen == true) ?trans('Online'):trans('Away') }}"></i>
                                        </div>
                                        <div class="">
                                            <h6 class="text-white mb-0 text-lowercase">@lang(optional($item->sender)->username)</h6>
                                            <span
                                                class="text-muted font-10">{{optional($item->sender)->total_trade}} @lang('Trades') |</span>
                                            <span
                                                class="text-muted font-10">@lang('Completion') {{number_format(optional($item->sender)->completed_trade*100/optional($item->sender)->total_trade,2)}}%</span>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </td>
                        @if($item->owner_id == auth()->user()->id)
                            <td data-label="@lang('Type')"><span
                                    class="badge {{($item->type == 'buy')? 'bg-success' : 'bg-warning'}}">@lang($item->type)</span>
                            </td>
                        @else
                            <td data-label="@lang('Type')"><span
                                    class="badge {{($item->type == 'buy')? 'bg-warning' : 'bg-success'}}">{{$item->type == 'buy'? 'sell':'buy'}}</span>
                            </td>
                        @endif
                        <td data-label="@lang('Currency')">@lang(optional($item->currency)->code)</td>
                        <td data-label="@lang('Payment Method')">
                            <div class="d-flex flex-wrap">
                                @foreach($item->gateways as $gateway)
                                    <span class="gateway-color"
                                          style="border-left-color: {{$gateway->color}}">{{$gateway->name}}</span>
                                @endforeach
                            </div>
                        </td>
                        <td data-label="@lang('Rate')">{{getAmount($item->rate+0,2)}} {{optional($item->currency)->code}}
                            /{{optional($item->receiverCurrency)->code}}</td>
                        <td data-label="@lang('Crypto Amount')">{{getAmount($item->receive_amount+0,8)}} {{optional($item->receiverCurrency)->code}}</td>
                        <td data-label="@lang('Status')">
                            @if($item->status == 0)
                                <span class="badge bg-warning">@lang('Pending')</span>
                            @elseif($item->status == 1)
                                <span class="badge bg-success">@lang('Buyer Paid')</span>
                            @elseif($item->status == 3)
                                <span class="badge bg-danger">@lang('Canceled')</span>
                            @elseif($item->status == 4)
                                <span class="badge bg-success">@lang('Completed')</span>
                            @elseif($item->status == 5)
                                <span class="badge bg-danger">@lang('Reported')</span>
                            @elseif($item->status == 6 || $item->status == 7)
                                <span class="badge bg-primary">@lang('Escrow Refunded')</span>
                            @elseif($item->status == 8)
                                <span class="badge bg-primary">@lang('Resolved')</span>

                            @endif
                        </td>
                        <td data-label="@lang('Requested On')">{{diffForHumans($item->created_at)}}</td>
                        <td data-label="@lang('Action')" class="action">
                            <a href="{{route('user.buyCurrencies.tradeDetails',$item->hash_slug)}}"
                               class="btn-custom p-6">
                                @lang('Details')</a>
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
                {{ $trades->appends($_GET)->links($theme.'partials.pagination') }}
            </ul>
        </nav>
    </div>
@endsection
