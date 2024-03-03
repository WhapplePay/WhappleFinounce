@extends('admin.layouts.app')
@section('title')
    @lang("Advertisements List")
@endsection
@section('content')
    <div class="page-header card card-primary m-0 m-md-4 my-4 m-md-0 p-5 shadow">
        <div class="row justify-content-between">
            <div class="col-md-12">
                <form action="{{route('admin.advertise.list')}}" method="get">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" name="username" value="{{@request()->username}}"
                                       class="form-control get-trx-id"
                                       placeholder="@lang('Search for username')">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" name="email" value="{{@request()->email}}"
                                       class="form-control get-username"
                                       placeholder="@lang('Search for email')">
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
                    <th scope="col">@lang('User')</th>
                    <th scope="col">@lang('Type')</th>
                    <th scope="col">@lang('Currency')</th>
                    <th scope="col">@lang('Payment Method')</th>
                    <th scope="col">@lang('Margin / Fixed')</th>
                    <th scope="col">@lang('Rate')</th>
                    <th scope="col">@lang('Payment Window')</th>
                    <th scope="col">@lang('Published')</th>
                    <th scope="col">@lang('Status')</th>
                    <th scope="col">@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($advertises as $key => $item)
                    <tr>
                    <tr>
                        <td data-label="@lang('SL No.')">{{++$key}}</td>
                        <td data-label="@lang('User')">
                            <a href="{{route('admin.user-edit',$item->user_id )}}">
                                <div class="d-flex no-block align-items-center">
                                    <div class="mr-3"><img
                                            src="{{ getFile(config('location.user.path') . optional($item->user)->image) }}"
                                            alt="user" class="rounded-circle" width="35" height="35"></div>
                                    <div class="mr-3">
                                        <h5 class="text-dark mb-0 font-16 font-weight-medium">@lang(optional($item->user)->username)</h5>
                                        <span
                                            class="text-muted font-10">{{optional($item->user)->total_trade}} @lang('Trades') |</span>
                                        @if(optional($item->user)->total_trade > 0)
                                            <span
                                                class="text-muted font-10">@lang('Completion') {{number_format(optional($item->user)->completed_trade*100/optional($item->user)->total_trade,2)}}
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
                        <td data-label="@lang('Type')"><span
                                class="badge badge-pill {{($item->type == 'buy')? 'badge-success' : 'badge-warning'}}">@lang($item->type)</span>
                        </td>
                        <td data-label="@lang('Currency')">@lang(optional($item->fiatCurrency)->code)</td>
                        <td data-label="@lang('Payment Method')">
                            @foreach($item->gateways as $gateway)
                                <span class="gateway-color"
                                      style="border-left-color: {{$gateway->color}}">{{$gateway->name}}</span>
                            @endforeach
                        </td>
                        <td data-label="@lang('Margin / Fixed')">@lang($item->price_type)
                            - {{number_format($item->price,2)}} {{$item->price_type == 'margin'? '%':optional($item->fiatCurrency)->code}}</td>
                        <td data-label="@lang('Rate')">@lang(number_format($item->rate,2)) {{optional($item->fiatCurrency)->code}}
                            /{{optional($item->cryptoCurrency)->code}}</td>
                        <td data-label="@lang('Payment Window')">@lang(optional($item->paymentWindow)->name)</td>
                        <td data-label="@lang('Published')"><span
                                class="badge badge-pill {{($item->status == 1)?'badge-success':'badge-danger'}}">{{($item->status == 1)?'Yes':'No'}}</span>
                        </td>
                        <td data-label="@lang('Status')"><span
                                class="badge badge-pill {{($item->status == 1)?'badge-success':'badge-danger'}}">{{($item->status == 1)?'Enabled':'Disabled'}}</span>
                        </td>
                        <td data-label="@lang('Action')">
                            <div class="dropdown show ">
                                <a class="dropdown-toggle p-3" href="#" id="dropdownMenuLink" data-toggle="dropdown"
                                   aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a href="javascript:void(0)" data-target="#details" data-toggle="modal"
                                       class="dropdown-item text-danger btn btn-sm btn-outline-info details" data-resource="{{$item}}"
                                       data-toggle="tooltip" title="Details" data-original-title="Details">
                                        <i class="fa fa-eye"></i>
                                        @lang('Details')
                                    </a>
                                    <a href="{{route('admin.feedback.list',$item->id)}}"
                                       class="dropdown-item btn btn-sm btn-outline-info"
                                       data-toggle="tooltip" title="Feedbacks" data-original-title="Feedbacks">
                                        <i class="fas fa-comment-dots"></i>
                                        @lang('Feedbacks')
                                    </a>
                                    <a href="{{route('admin.trade.list','?adId='.$item->id)}}"
                                       class="dropdown-item btn btn-sm btn-outline-dark"
                                       data-toggle="tooltip" title="Trade Lists" data-original-title="Trade Lists">
                                        <i class="fas fa-chart-line"></i>
                                        @lang('Trade Lists')
                                    </a>
                                    @if($item->status == 1)
                                        <button
                                            class="dropdown-item btn btn-sm btn-outline-danger disableBtn"
                                            data-target="#enableDisable"
                                            data-toggle="modal"
                                            data-route="{{route('admin.advertise.disable',$item->id)}}"
                                            data-toggle="tooltip" title="Disable">
                                            <i class="fas fa-ban"></i>
                                            @lang('Disable')
                                        </button>
                                    @else
                                        <button
                                            class="dropdown-item btn btn-sm btn-outline-info enableBtn"
                                            data-target="#enableDisable"
                                            data-toggle="modal"
                                            data-route="{{route('admin.advertise.enable',$item->id)}}"
                                            data-toggle="tooltip" title="Enable">
                                            <i class="fas fa-check-circle"></i>
                                            @lang('Enable')
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center text-danger" colspan="10">@lang('No Data Found')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            {{ $advertises->links('partials.pagination') }}
        </div>
    </div>
    <!-- Return Buyer Modal -->
    <div id="details" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="primary-header-modalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="primary-header-modalLabel">@lang('Advertisement Details')
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="rate">
                        <li>
                            <span>@lang('Type:')</span><span class="caption type mr-4"></span>
                        </li>
                        <li class="my-2">
                            <span>@lang('Crypto Currency:')</span><span class="caption crypto mr-4"></span>
                        </li>
                        <li>
                            <span>@lang('Fiat Currency:')</span><span class="caption fiat mr-4"></span>
                        </li>
                        <li class="my-2">
                            <span>@lang('Payment Methods:')</span><span class="caption paymentMethod mr-4"></span>
                        </li>
                        <li>
                            <span>@lang('Payment Window:')</span><span class="caption paymentWindow mr-4"></span>
                        </li>
                        <li class="my-2">
                            <span>@lang('Price Type:')</span><span class="caption priceType mr-4"></span>
                        </li>
                        <li>
                            <span>@lang('Minimum Limit:')</span><span class="caption minimum mr-4"></span>
                        </li>
                        <li class="my-2">
                            <span>@lang('Maximum Limit')</span><span class="caption maximum mr-4"></span>
                        </li>
                        <li>
                            <span>@lang('Rate:')</span><span class="caption rates mr-4"></span>
                        </li>
                        <li class="my-2">
                            <span>@lang('Status:')</span><span class="caption status mr-4"></span>
                        </li>
                        <hr>
                        <li>
                            <span class="text-decoration-underline">@lang('Payment Details:')</span><span
                                class="caption paymentDetails mb-4"></span>
                        </li>
                        <li class="my-2">
                            <span>@lang('Terms Of Trades:')</span><span class="caption termsTrade"></span>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Enable Disable Modal -->
    <div id="enableDisable" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="primary-header-modalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title modalTitle" id="primary-header-modalLabel">
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <p class="message"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">@lang('Close')</button>
                    <form action="" method="post" id="enableDisableForm">
                        @csrf
                        <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        'use script'
        $(document).on('click', '.disableBtn', function (e) {
            var route = $(this).data('route');
            $('.modalTitle').text('Disable Confirmation')
            $('.message').text('Are you sure you want to disable this advertisement')
            $('#enableDisableForm').attr('action', route)
        });

        $(document).on('click', '.enableBtn', function (e) {
            var route = $(this).data('route');
            $('.modalTitle').text('Enable Confirmation')
            $('.message').text('Are you sure you want to enable this advertisement')
            $('#enableDisableForm').attr('action', route)
        });

        $(document).on('click', '.details', function (e) {
            $('.paymentMethod').html('');
            var obj = $(this).data('resource');
            if (obj.type == 'buy') {
                $('.type').removeClass('badge badge-warning');
                $('.type').addClass('badge badge-success');
            } else {
                $('.type').removeClass('badge badge-success');
                $('.type').addClass('badge badge-warning');
            }
            $('.type').text(obj.type);
            $('.crypto').text(obj.crypto_currency.name);
            $('.fiat').text(obj.fiat_currency.name);

            obj.gateways.forEach((item) => {
                console.log(item.name)
                $('.paymentMethod').append(`<span
                    class="gateway-color" style="border-left-color: ${item.color}">${item.name}</span>`);
            });

            $('.paymentWindow').text(obj.payment_window.name);
            $('.priceType').text(obj.price_type);
            $('.minimum').text(obj.minimum_limit + " " + obj.fiat_currency.code);
            $('.maximum').text(obj.maximum_limit + " " + obj.fiat_currency.code);
            $('.rates').text(obj.rate.toFixed(2) + " " + obj.fiat_currency.code + "/" + obj.crypto_currency.code);
            if (obj.status == '1') {
                var status = "Enabled"
                $('.status').removeClass('badge badge-danger');
                $('.status').addClass('badge badge-success');
            } else {
                var status = "Disabled"
                $('.status').removeClass('badge badge-success');
                $('.status').addClass('badge badge-danger');
            }
            $('.status').text(status);
            $('.paymentDetails').text(obj.payment_details);
            $('.termsTrade').text(obj.terms_of_trade);
        });
    </script>
@endpush
