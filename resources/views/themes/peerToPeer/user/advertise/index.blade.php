@extends($theme.'layouts.user')
@section('title')
    @lang('Advertisements')
@endsection
@section('content')
    <div class="container-fluid">
        <div class="main row">
            <div class="col-12">
                <div class="search-bar">
                    <form action="{{route('user.advertisements.list')}}" method="get">
                        <div class="row g-3 align-items-end">
                            <div class="input-box col-lg-3">
                                <label for="">@lang('Currency Code')</label>
                                <div class="form-group">
                                    <input type="text" name="currencyCode"
                                           value="{{@request()->currencyCode}}"
                                           class="form-control" placeholder="@lang('Currency Code')">
                                </div>
                            </div>

                            <div class="input-box col-lg-3">
                                <label for="">@lang('Type')</label>
                                <select class="form-select" aria-label="Default select example" name="type">
                                    <option selected disabled>@lang('Select Type')</option>
                                    <option
                                        value="buy" {{@request()->type == 'buy'?'selected':''}}>@lang('Buy')</option>
                                    <option
                                        value="sell" {{@request()->type == 'sell'?'selected':''}}>@lang('Sell')</option>
                                </select>
                            </div>

                            <div class="input-box col-lg-3">
                                <label for="">@lang('Status')</label>
                                <select class="form-select" aria-label="Default select example" name="status">
                                    <option selected disabled>@lang('Select Status')</option>
                                    <option
                                        value="1" {{@request()->status == '1'?'selected':''}}>@lang('Enabled')</option>
                                    <option
                                        value="0" {{@request()->status == '0'?'selected':''}}>@lang('Disable')</option>
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
            <div class="text-end mb-4 me-3">
                <a href="{{route('user.advertisements.create')}}">
                    <button class="btn-modify">
                        @lang('New Ad')
                    </button>
                </a>
            </div>
            <table class="table table-striped" id="service-table">
                <thead>
                <tr>
                    <th>@lang('SL No.')</th>
                    <th>@lang('Type')</th>
                    <th>@lang('Currency')</th>
                    <th>@lang('Payment Method')</th>
                    <th>@lang('Margin / Fixed')</th>
                    <th>@lang('Rate')</th>
                    <th>@lang('Payment Window')</th>
                    <th>@lang('Published')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($advertises as $key => $item)
                    <tr>
                        <td data-label="@lang('SL No.')">{{++$key}}</td>
                        <td data-label="@lang('Type')"><span
                                class="badge {{($item->type == 'buy')? 'bg-success' : 'bg-warning'}}">@lang($item->type)</span>
                        </td>
                        <td data-label="@lang('Currency')">@lang(optional($item->fiatCurrency)->code)</td>
                        <td data-label="@lang('Payment Method')">
                            <div class="d-flex flex-wrap">
                                @foreach($item->gateways as $gateway)
                                    <span class="gateway-color"
                                          style="border-left-color: {{$gateway->color}}">{{$gateway->name}}</span>
                                @endforeach
                            </div>
                        </td>
                        <td data-label="@lang('Margin / Fixed')">@lang($item->price_type)
                            - {{number_format($item->price,2)}} {{$item->price_type == 'margin'? '%':optional($item->fiatCurrency)->code}}</td>
                        <td data-label="@lang('Rate')">@lang(number_format($item->rate,3)) {{optional($item->fiatCurrency)->code}}
                            /{{optional($item->cryptoCurrency)->code}}</td>
                        <td data-label="@lang('Payment Window')">@lang(optional($item->paymentWindow)->name)</td>
                        <td data-label="@lang('Published')"><span
                                class="badge {{($item->status == 1)?'bg-success':'bg-danger'}}">{{($item->status == 1)?'Yes':'No'}}</span>
                        </td>
                        <td data-label="@lang('Status')"><span
                                class="badge {{($item->status == 1)?'bg-success':'bg-danger'}}">{{($item->status == 1)?'Enabled':'Disabled'}}</span>
                        </td>
                        <td data-label="@lang('Action')" class="action">
                            <div class="sidebar-dropdown-items">
                                <button
                                    type="button"
                                    class="dropdown-toggle"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="far fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a href="{{route('user.feedback.list',$item->id)}}"
                                           class="dropdown-item">
                                            <i class="far fa-thumbs-up"></i> @lang('Feedback')
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('user.advertisements.edit',$item->id)}}"
                                           class="dropdown-item">
                                            <i class="far fa-edit"></i> @lang('Edit')
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{route('user.trade.list','?adId='.$item->id)}}"
                                           class="dropdown-item">
                                            <i class="fal fa-file-spreadsheet"></i> @lang('Trade Lists')
                                        </a>
                                    </li>
                                    <li>
                                        @if($item->status == 0)
                                            <form action="{{route('user.advertisements.enable',$item->id)}}"
                                                  method="post">
                                                @csrf
                                                <button class="dropdown-item" type="submit">
                                                    <i class="far fa-eye"></i> @lang('Enable')
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{route('user.advertisements.disable',$item->id)}}"
                                                  method="post">
                                                @csrf
                                                <button class="dropdown-item" type="submit">
                                                    <i class="far fa-eye-slash"></i> @lang('Disable')
                                                </button>
                                            </form>
                                        @endif
                                    </li>
                                </ul>
                            </div>
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
                {{ $advertises->appends($_GET)->links($theme.'partials.pagination') }}
            </ul>
        </nav>
    </div>
@endsection
