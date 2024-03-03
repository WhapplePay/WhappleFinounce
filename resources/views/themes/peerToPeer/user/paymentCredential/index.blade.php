@extends($theme.'layouts.user')
@section('title',trans('Payment Credential'))

@section('content')
    <!-- main -->
    <div class="container-fluid">
        <div class="main row">
            <!-- edit profile section -->
            <div class="row">
                <div class="col-md-8">
                    <div class="edit-profile-section">
                        <h6 class="title mb-4">@lang('Select Payment Method')</h6>
                        <div class="table-parent table-responsive">
                            <table class="table  " id="service-table">

                                <tbody>
                                @forelse($infos as $key => $item)
                                    <tr class="active">
                                        <td data-label="@lang('Payment method')">
                                            <span class="gateway-color"
                                                  style="border-left-color: {{optional($item->gateway)->color}}">{{optional($item->gateway)->name}}</span>
                                        </td>

                                        <td data-label="@lang('Information')">

                                            <div class="d-lg-flex d-block align-items-center">
                                                <div class="">
                                                    @foreach(collect($item->information)->take(2) as $inKey => $inData)
                                                        @if($loop->first)
                                                            <h6 class="text-white mb-0 text-lowercase">
                                                                {{$inData->fieldValue}}</h6>
                                                        @else
                                                            <span
                                                                class="text-muted font-10">{{$inData->fieldValue}} </span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>

                                        </td>
                                        <td data-label="@lang('Action')" class="action">
                                            <a href="javascript:void(0)"
                                               data-name="{{optional($item->gateway)->name}}"
                                               data-route="{{route('user.sellCurrencies.gatewayInfoUpdate',$item->id)}}"
                                               data-input_form="{{json_encode($item->information)}}"
                                               class="btn btn-sm btn-outline-warning  viewData"
                                               data-bs-target="#viewModal"
                                               data-bs-toggle="modal">

                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <a href="javascript:void(0)"
                                               class="btn btn-sm btn-outline-success selectBtn"
                                               data-gateway_id="{{$item->gateway_id}}"
                                               data-adds_id="{{$advertise->id}}"
                                               data-id="{{$item->id}}">
                                                <i class="fa fa-check-circle"></i>
                                            </a>

                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @if($advertise->gateways)
                    <div class="col-md-4">
                        <div class="edit-profile-section">
                            <h6 class="title">@lang('Supported Payment Methods')</h6>
                            <div class="list-group" id="list-tab" role="tablist">
                                @foreach($advertise->gateways as $key => $item)
                                    <a class="bg-transparent bgDark list-group-item list-group-item-action text-capitalize addGatewayInfo"
                                       href="javascript:void(0)"
                                       data-name="{{$item->name}}"
                                       data-id="{{$item->id}}"
                                       data-input_form="{{json_encode($item->input_form)}}"
                                       data-bs-target="#addModal" data-bs-toggle="modal"><i
                                            class="fa fa-plus"></i> @lang('Add') @lang($item->name)</a>
                                @endforeach
                            </div>

                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title addModalLabel" id="addModalLabel"></h6>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>
                <form action="{{route('user.sellCurrencies.gatewayInfoSave')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="gatewayId" value="" class="gatewayId">
                        <div class="row g-4  withdraw-detail">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-custom btn2" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn-custom">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title viewModalLabel" id="viewModalLabel"></h6>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>
                <form action="" method="post" class="viewAction">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-4  view-detail">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" name="submitBtn" value="delete"
                                class="btn btn-danger red">@lang('Delete')</button>
                        <button type="submit" name="submitBtn" value="update"
                                class="btn btn-success">@lang('Update')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    @if ($errors->any())
        @php
            $collection = collect($errors->all());
            $errors = $collection->unique();
        @endphp
        <script>
            "use strict";
            @foreach ($errors as $error)
            Notiflix.Notify.Failure("{{trans($error)}}");
            @endforeach
        </script>
    @endif

    <script>
        "use strict";

        $(document).on('click', '.viewData', function () {
            $('.view-detail').html('');
            var $name = $(this).data('name');
            var route = $(this).data('route');
            $('.viewAction').attr('action', route);
            var $input_form = Object.entries($(this).data('input_form'));
            $('.viewModalLabel').text(`Update ${$name} Information`)

            var list = [];
            $input_form.map(function (item, i) {
                if (item[1].type == "text") {
                    var singleInfo = `<div class="input-box col-md-12">
                                <label>${item[1].label}
                                        ${(item[1].validation == "required") ? '<span class="text-danger">*</span>' : ''}
                                </label>
                                <input type="text" name="${item[1].name}" value="${item[1].fieldValue}"
                                       placeholder="${(item[1].validation != "required") ? 'optional' : ' ' + item[1].label}"
                                       class="form-control" ${(item[1].validation == "required") ? 'required' : ''}>
                            </div>`;
                } else {
                    var singleInfo = `<div class="input-box col-md-12">
                                <label>${item[1].label}
                                    ${(item[1].validation == "required") ? '<span class="text-danger">*</span>' : ''}
                                </label>
                                <textarea type="text" name="${item[1].name}"
                                          class="form-control" ${(item[1].validation == "required") ? 'required' : ''}
                                       placeholder="${(item[1].validation != "required") ? 'optional' : ' ' + item[1].label}">${item[1].fieldValue}</textarea>
                            </div>`;
                }
                list[i] = singleInfo
            });
            $('.view-detail').html(list);

        });

        $(document).on('click', '.addGatewayInfo', function () {
            $('.withdraw-detail').html('');
            var $name = $(this).data('name');
            var $id = $(this).data('id');
            $('.gatewayId').val($id);
            var $input_form = Object.entries($(this).data('input_form'));

            $('.addModalLabel').text(`Add ${$name} Information`)

            var list = [];
            $input_form.map(function (item, i) {
                if (item[1].type == "text") {
                    var singleInfo = `<div class="input-box col-md-12">
                                <label>${item[1].label}
                                        ${(item[1].validation == "required") ? '<span class="text-danger">*</span>' : ''}
                                </label>
                                <input type="text" name="${item[1].name}"
                                       placeholder="${(item[1].validation != "required") ? 'optional' : ' ' + item[1].label}"
                                       class="form-control" ${(item[1].validation == "required") ? 'required' : ''}>
                            </div>`;
                } else {
                    var singleInfo = `<div class="input-box col-md-12">
                                <label>${item[1].label}
                                    ${(item[1].validation == "required") ? '<span class="text-danger">*</span>' : ''}
                                </label>
                                <textarea type="text" name="${item[1].name}"
                                          class="form-control" ${(item[1].validation == "required") ? 'required' : ''}
                                       placeholder="${(item[1].validation != "required") ? 'optional' : ' ' + item[1].label}"></textarea>
                            </div>`;
                }
                list[i] = singleInfo
            });
            $('.withdraw-detail').html(list);
        });
        $(document).on('click', '.selectBtn', function () {
            var gateway_id = $(this).data('gateway_id');
            var id = $(this).data('id');
            var adds_id = $(this).data('adds_id');
            $.ajax({
                url: "{{ route('user.sellCurrencies.gatewaySelect') }}",
                method: 'post',
                data: {
                    gateway_id: gateway_id, id: id, adds_id: adds_id,
                },
                success: function (response) {
                    window.location.href = response.url
                }
            });
        });
    </script>
@endpush
