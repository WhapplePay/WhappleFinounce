@extends('admin.layouts.app')
@section('title', trans($page_title))
@section('content')

    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered no-wrap" id="zero_config">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">@lang('Name')</th>
                        <th scope="col">@lang('Type')</th>
                        <th scope="col">@lang('Status')</th>
                        
                            <th scope="col">@lang('Action')</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($methods as $method)
                        <tr>
                            <td data-label="Name">
                                <a href="javascript:void(0)">
                                    <div class="d-lg-flex d-block align-items-center ">
                                        <div class="mr-3"><img
                                                src="{{getFile(config('location.payoutMethod.path').$method->image)}}"
                                                alt="user" class="rounded-circle" width="45" height="45">
                                        </div>
                                        <div class="">
                                            <h5 class="text-dark mb-0 font-16 font-weight-medium">
                                                {{ $method->name }}</h5>
                                        </div>
                                    </div>
                                </a>
                            </td>

                            <td data-label="@lang('Type')">
                                @if($method->is_automatic == 1)
                                    <span class="text-success font-weight-bold">@lang('Automatic')</span>
                                @else
                                    <span class="text-warning font-weight-bold">@lang('Manual')</span>
                                @endif
                            </td>
                            <td data-label="@lang('Status')">
                            <span
                                class="badge badge-pill badge-{{($method->status == 1) ?'success' : 'danger'}}">{{($method->status == 1) ?trans('Active') : trans('Deactive')}}</span>
                            </td>

                               <td data-label="@lang('Action')">
                                    <a href="{{ route('admin.payout.method.edit', $method->id) }}"
                                       class="btn btn-sm btn-primary"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       data-original-title="@lang('Edit Payment Methods Info')">
                                        <i class="fa fa-edit"></i></a>
                                    @if($method->status == 0)
                                        <button class="btn btn-sm  btn-success enable"
                                                data-route="{{route('admin.payout.methodStatus',$method->id)}}"
                                                data-target="#statusChange"
                                                data-toggle="modal">@lang('Enable')</button>
                                    @endif
                                </td>
                            
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center text-danger" colspan="8">
                                @lang('No Data Found')
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="statusChange" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content ">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="myModalLabel">@lang('Status Change Confirmation')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>

                <form method="POST" class="actionRoute" action="">
                    @csrf
                    <div class="modal-body">
                        <div class="add-text">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')
                        </button>
                        <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('style-lib')
    <link href="{{asset('assets/admin/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush
@push('js')
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/datatable-basic.init.js') }}"></script>
@endpush
@push('extra-script')
    <script>
        'use strict'
        $(document).on('click', '.enable', function () {
            $('.add-text').html('');
            let route = $(this).data('route');
            $('.add-text').append(`Are you sure want to enable this payout method`)
            $('.actionRoute').attr('action', route);
        })
    </script>
@endpush
