@extends('backend.franchise.employees.layout')

@section('panel_content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Ticket') }} #{{ $ticket->code }}</h1>
            <p class="text-muted mb-0">{{ $ticket->subject }}</p>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('franchise.employee.support_tickets.index') }}" class="btn btn-secondary btn-sm">
                <i class="las la-arrow-left mr-1"></i>{{ translate('Back to Tickets') }}
            </a>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="font-weight-bold">{{ translate('Original Message') }}</span>
        <span class="badge badge-{{ $ticket->status == 'solved' ? 'success' : ($ticket->status == 'open' ? 'secondary' : 'danger') }} text-capitalize">
            {{ translate(ucfirst($ticket->status)) }}
        </span>
    </div>
    <div class="card-body">
        <p>{!! nl2br(e($ticket->details)) !!}</p>
        
        @if($ticket->files)
            <div class="mt-3">
                @foreach (explode(',', $ticket->files) as $file)
                    @php $file_detail = get_single_uploaded_file($file) @endphp
                    @if($file_detail)
                        <a href="{{ uploaded_asset($file) }}" target="_blank" class="badge badge-lg badge-inline badge-soft-primary mb-1">
                            <i class="las la-paperclip mr-1 text-muted"></i>{{ $file_detail->file_original_name.'.'.$file_detail->extension }}
                        </a>
                    @endif
                @endforeach
            </div>
        @endif
        
        <small class="text-muted d-block mt-2">{{ $ticket->created_at->format('d M Y, h:i A') }}</small>
    </div>
</div>

@foreach($ticket_replies as $reply)
<div class="card mb-3 {{ $reply->user_id == Auth::user()->id ? 'border-primary' : '' }}">
    <div class="card-header d-flex justify-content-between align-items-center bg-light">
        <span class="font-weight-bold">
            @if($reply->user && $reply->user->user_type == 'admin')
                <i class="las la-headset mr-1 text-primary"></i>{{ translate('Support Team') }}
            @else
                <i class="las la-user mr-1"></i>{{ translate('You') }}
            @endif
        </span>
        <small class="text-muted">{{ $reply->created_at->format('d M Y, h:i A') }}</small>
    </div>
    <div class="card-body">
        <p>{!! nl2br(e($reply->reply)) !!}</p>
        
        @if($reply->files)
            <div class="mt-3">
                @foreach (explode(',', $reply->files) as $file)
                    @php $file_detail = get_single_uploaded_file($file) @endphp
                    @if($file_detail)
                        <a href="{{ uploaded_asset($file) }}" target="_blank" class="badge badge-lg badge-inline badge-soft-primary mb-1">
                            <i class="las la-paperclip mr-1 text-muted"></i>{{ $file_detail->file_original_name.'.'.$file_detail->extension }}
                        </a>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</div>
@endforeach

@if($ticket->status != 'solved')
<div class="card shadow-sm">
    <div class="card-header bg-soft-primary">
        <h5 class="mb-0 h6">{{ translate('Reply') }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('franchise.employee.support_tickets.reply') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
            <div class="form-group">
                <textarea name="reply" class="form-control" rows="4" required placeholder="{{ translate('Type your reply...') }}"></textarea>
            </div>
            
            <div class="form-group">
                <label>{{ translate('Attachments') }}</label>
                <div class="input-group" data-toggle="aizuploader" data-type="image,archive,document" data-multiple="true">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-soft-secondary font-weight-medium text-muted">{{ translate('Browse')}}</div>
                    </div>
                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                    <input type="hidden" name="attachments" class="selected-files">
                </div>
                <div class="file-preview box sm">
                </div>
            </div>
            
            <div class="text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="las la-paper-plane mr-1"></i>{{ translate('Send Reply') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
