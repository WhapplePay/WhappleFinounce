@extends('admin.layouts.app')
@section('title')
    @lang("Trades List")
@endsection
@section('content')
    <div class="page-header card card-primary m-0 m-md-4 my-4 m-md-0 p-5 shadow">
        <div class="row justify-content-between">
            <div class="col-md-12">
                <form action="{{route('admin.trade.list')}}" method="get">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="tradeNumber" value="{{@request()->tradeNumber}}"
                                       class="form-control get-trx-id"
                                       placeholder="@lang('Search for Trade Number')">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="owner" value="{{@request()->owner}}"
                                       class="form-control get-username"
                                       placeholder="@lang('Search for Owner username or email')">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="sender" value="{{@request()->sender}}"
                                       class="form-control get-username"
                                       placeholder="@lang('Search for Requester username or email')">
                            </div>
                        </div>
                        <div class="col-md-3">
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
                    <th scope="col">@lang('Trade Number')</th>
                    <th scope="col">@lang('Owner')</th>
                    <th scope="col">@lang('Requester')</th>
                    <th scope="col">@lang('Amount')</th>
                    <th scope="col">@lang('Payment Method')</th>
                    <th scope="col">@lang('Exchange Rate')</th>
                    <th scope="col">@lang('Crypto Amount')</th>
                    <th scope="col">@lang('Status')</th>
                    <th scope="col">@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($trades as $k => $item)
                    <tr>
                        <td data-label="@lang('Trade Number')">{{$item->trade_number}}</td>
                        <td data-label="@lang('Owner')">
                            <a href="{{route('admin.user-edit',$item->owner_id )}}">
                                <div class="d-flex no-block align-items-center">
                                    <div class="mr-3"><img
                                            src="{{ getFile(config('location.user.path') . optional($item->owner)->image) }}"
                                            alt="user" class="rounded-circle" width="35" height="35"></div>
                                    <div class="mr-3">
                                        <h5 class="text-dark mb-0 font-16 font-weight-medium">@lang(optional($item->owner)->username)</h5>
                                        <span
                                            class="text-muted font-10">{{optional($item->owner)->total_trade}} @lang('Trades') |</span>
                                        @if(optional($item->owner)->total_trade > 0)
                                            <span
                                                class="text-muted font-10">@lang('Completion') {{number_format(optional($item->owner)->completed_trade*100/optional($item->owner)->total_trade,2)}}
                                                %</span>
                                        @else
                                            <span
                                                class="text-muted font-10">@lang('Completion') 0
                                                %</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </td>
                        <td data-label="@lang('Requester')">
                            <a href="{{route('admin.user-edit',$item->sender_id )}}">
                                <div class="d-flex no-block align-items-center">
                                    <div class="mr-3"><img
                                            src="{{ getFile(config('location.user.path') . optional($item->sender)->image) }}"
                                            alt="user" class="rounded-circle" width="35" height="35"></div>
                                    <div class="mr-3">
                                        <h5 class="text-dark mb-0 font-16 font-weight-medium">@lang(optional($item->sender)->username)</h5>
                                        <span
                                            class="text-muted font-10">{{optional($item->sender)->total_trade}} @lang('Trades') |</span>
                                        @if(optional($item->sender)->total_trade > 0)
                                            <span
                                                class="text-muted font-10">@lang('Completion') {{number_format(optional($item->sender)->completed_trade*100/optional($item->sender)->total_trade,2)}}
                                                %</span>
                                        @else
                                            <span
                                                class="text-muted font-10">@lang('Completion') 0
                                                %</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </td>
                        <td data-label="@lang('Amount')">{{$item->pay_amount}} {{optional($item->currency)->code}}</span>
                        </td>
                        <td data-label="@lang('Payment Method')">@foreach($item->gateways as $gateway)
                                <span class="gateway-color"
                                      style="border-left-color: {{$gateway->color}}">{{$gateway->name}}</span>
                            @endforeach</td>
                        <td data-label="@lang('Exchange Rate')">{{getAmount($item->rate+0,2)}} {{optional($item->currency)->code}}
                            /{{optional($item->receiverCurrency)->code}}</td>
                        <td data-label="@lang('Crypto Amount')">{{getAmount($item->receive_amount,8)}} {{optional($item->receiverCurrency)->code}}</span>
                        </td>
                        <td data-label="@lang('Status')">
                            @if($item->status == 0)
                                <span class="badge badge-warning">@lang('Pending')</span>
                            @elseif($item->status == 1)
                                <span class="badge badge-success">@lang('Buyer Paid')</span>
                            @elseif($item->status == 3)
                                <span class="badge badge-danger">@lang('Canceled')</span>
                            @elseif($item->status == 4)
                                <span class="badge badge-success">@lang('Completed')</span>
                            @elseif($item->status == 5)
                                <span class="badge badge-danger">@lang('Reported')</span>
                            @elseif($item->status == 6 || $item->status == 7)
                                <span class="badge badge-info">@lang('Escrow Funded')</span>
                                   @elseif($item->status == 8)
                                <span class="badge badge-primary">@lang('Resolved')</span>
                            @endif
                        </td>
                        <td data-label="@lang('Action')">
                            <a href="{{route('admin.trade.Details',$item->hash_slug)}}"
                               class="btn btn-sm btn-outline-info"
                               data-toggle="tooltip" title="" data-original-title="Details">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center text-danger" colspan="8">@lang('No Trades Data')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            {{ $trades->links('partials.pagination') }}
        </div>
    </div>
@endsection

@push('js')
@endpush
