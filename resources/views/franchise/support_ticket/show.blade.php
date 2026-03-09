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
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="attachments[]" id="attachments" accept="image/*" multiple>
                                    <label class="custom-file-label" for="attachments">{{ translate('Choose file') }}</label>
                                </div>
                                <div class="attachments-preview-container mt-3 d-flex flex-wrap">
                                </div>
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

@section('script')
    <script type="text/javascript">
        $(document).on('change', '.custom-file-input', function() {
            var input = $(this);
            var files = this.files;
            var label = input.siblings('.custom-file-label');
            
            if (files.length > 0) {
                if(input.attr('multiple')) {
                    label.html(files.length + ' {{ translate("files selected") }}');
                } else {
                    label.html(files[0].name);
                }
                
                var container = input.closest('.form-group').find('.attachments-preview-container');
                container.html(''); // Clear previous previews
                $.each(files, function(i, file) {
                    if (file.type.match('image.*')) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            container.append('<div class="mr-2 mb-2"><img class="img-fluid rounded shadow-sm" src="' + e.target.result + '" style="max-height: 100px; border: 1px solid #ddd; background: #f8f9fa; padding: 5px;"></div>');
                        }
                        reader.readAsDataURL(file);
                    }
                });
            } else {
                label.html('{{ translate("Choose file") }}');
            }
        });
    </script>
@endsection
