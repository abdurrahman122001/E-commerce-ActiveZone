@extends('franchise.layouts.app')

@section('panel_content')
    <div class="col-lg-10 mx-auto">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header bg-soft-primary border-bottom-0 py-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0 h6 fw-700 text-primary">{{ $ticket->subject }}</h5>
                        <div class="mt-1 fs-12 text-muted">
                            <span class="mr-3">#{{ $ticket->code }}</span>
                            <span>{{ $ticket->created_at->format('d M, Y H:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item px-0 border-0">
                        <div class="d-flex mb-3">
                            <span class="avatar avatar-sm flex-shrink-0">
                                @if($ticket->user && $ticket->user->avatar_original != null)
                                    <img src="{{ uploaded_asset($ticket->user->avatar_original) }}">
                                @else
                                    <img src="{{ static_asset('assets/img/avatar-placeholder.png') }}">
                                @endif
                            </span>
                            <div class="ml-3 flex-grow-1">
                                <div class="bg-light p-3 rounded-lg border">
                                    <div class="fw-700 mb-1 text-dark">{{ $ticket->user->name ?? translate('Unknown User') }}</div>
                                    <div class="text-secondary lh-1-6">{{ $ticket->details }}</div>
                                    @if($ticket->files)
                                        <div class="mt-3">
                                            @foreach (explode(',', $ticket->files) as $file)
                                                @php $file_detail = \App\Models\Upload::find($file) @endphp
                                                @if($file_detail)
                                                    <a href="{{ uploaded_asset($file) }}" download="" class="badge badge-lg badge-inline badge-light mb-1 border mr-1">
                                                        <i class="las la-download text-mutedmr-1"></i>
                                                        {{ $file_detail->file_original_name }}.{{ $file_detail->extension }}
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                    @foreach ($ticket_replies as $reply)
                        <li class="list-group-item px-0 border-0">
                            <div class="d-flex mb-3 {{ $reply->user && $reply->user->user_type == 'admin' ? 'flex-row-reverse' : '' }}">
                                <span class="avatar avatar-sm flex-shrink-0 {{ $reply->user && $reply->user->user_type == 'admin' ? 'ml-3' : 'mr-3' }}">
                                    @if($reply->user && $reply->user->avatar_original != null)
                                        <img src="{{ uploaded_asset($reply->user->avatar_original) }}">
                                    @else
                                        <img src="{{ static_asset('assets/img/avatar-placeholder.png') }}">
                                    @endif
                                </span>
                                <div class="flex-grow-1">
                                    <div class="{{ $reply->user && $reply->user->user_type == 'admin' ? 'bg-soft-primary border-primary' : 'bg-light border' }} p-3 rounded-lg border">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="fw-700 text-dark">{{ $reply->user->name ?? translate('Unknown User') }}</span>
                                            <span class="fs-11 text-muted">{{ $reply->created_at->format('d M, Y H:i A') }}</span>
                                        </div>
                                        <div class="text-secondary lh-1-6">{{ $reply->reply }}</div>
                                        @if($reply->files)
                                            <div class="mt-3">
                                                @foreach (explode(',', $reply->files) as $file)
                                                    @php $file_detail = \App\Models\Upload::find($file) @endphp
                                                    @if($file_detail)
                                                        <a href="{{ uploaded_asset($file) }}" download="" class="badge badge-lg badge-inline badge-light mb-1 border mr-1">
                                                            <i class="las la-download text-muted mr-1"></i>
                                                            {{ $file_detail->file_original_name }}.{{ $file_detail->extension }}
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                @if($ticket->status != 'solved')
                    <div class="border-top pt-4">
                        <form action="{{ route('franchise.support_ticket_reply.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                            <div class="form-group mb-3">
                                <label class="fw-700 text-dark">{{ translate('Reply') }}</label>
                                <textarea class="form-control" name="reply" rows="4" placeholder="{{ translate('Type your reply here...') }}" required></textarea>
                            </div>
                            <div class="form-group mb-4">
                                <label class="fw-700 text-dark">{{ translate('Attachments') }}</label>
                                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="attachments" class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary px-5">{{ translate('Send Reply') }}</button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="alert alert-info text-center py-4 rounded-lg">
                        <i class="las la-info-circle la-2x mb-2 d-block"></i>
                        {{ translate('This ticket is solved and closed.') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
