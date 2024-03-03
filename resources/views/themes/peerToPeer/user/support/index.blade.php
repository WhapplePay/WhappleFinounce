@extends($theme.'layouts.user')
@section('title',__($page_title))

@section('content')
    <div class="container-fluid">
        <div class="main row">
            <div class="col-12">
                <h5>{{$page_title}}</h5>
                <div class="table-parent table-responsive">
                    <div class="text-end mb-4 me-3">
                        <a href="{{route('user.ticket.create')}}">
                            <button class="btn-ticket">
                                @lang('Create Ticket')
                            </button>
                        </a>
                    </div>
                    <table class="table table-striped" id="service-table">
                        <thead>
                        <tr>
                            <th scope="col">@lang('Subject')</th>
                            <th scope="col">@lang('Status')</th>
                            <th scope="col">@lang('Last Reply')</th>
                            <th scope="col">@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($tickets as $key => $ticket)
                            <tr>
                                <td data-label="@lang('Subject')">
                                                    <span
                                                        class="font-weight-bold"> [{{ trans('Ticket#').$ticket->ticket }}
                                                        ] {{ $ticket->subject }} </span>
                                </td>
                                <td data-label="@lang('Status')">
                                    @if($ticket->status == 0)
                                        <span
                                            class="badge bg-success">@lang('Open')</span>
                                    @elseif($ticket->status == 1)
                                        <span
                                            class="badge bg-primary">@lang('Answered')</span>
                                    @elseif($ticket->status == 2)
                                        <span
                                            class="badge bg-warning">@lang('Replied')</span>
                                    @elseif($ticket->status == 3)
                                        <span class="badge bg-dark">@lang('Closed')</span>
                                    @endif
                                </td>

                                <td data-label="@lang('Last Reply')">
                                    {{diffForHumans($ticket->last_reply) }}
                                </td>

                                <td data-label="@lang('Action')">
                                    <a href="{{ route('user.ticket.view', $ticket->ticket) }}"
                                       class="btn btn-sm btn-detail"
                                       data-toggle="tooltip" title="" data-original-title="Details">
                                        <i class="fa fa-eye"></i>
                                    </a>
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
                        {{ $tickets->appends($_GET)->links($theme.'partials.pagination') }}
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection
