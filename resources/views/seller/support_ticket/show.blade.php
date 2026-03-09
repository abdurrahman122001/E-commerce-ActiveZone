@extends('seller.layouts.app')

@section('panel_content')
    <div class="card">
        <div class="card-header row gutters-5">
            <div class="text-center text-md-left">
                <h5 class="mb-md-0 h5">{{ $ticket->subject }} #{{ $ticket->code }}</h5>
               <div class="mt-2">
                   <span> {{ $ticket->user->name }} </span>
                   <span class="ml-2"> {{ $ticket->created_at }} </span>
                   <span class="badge badge-inline badge-secondary ml-2"> {{ translate(ucfirst($ticket->status)) }} </span>
               </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{route('seller.support_ticket.reply_store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="ticket_id" value="{{$ticket->id}}" required>
                <input type="hidden" name="user_id" value="{{$ticket->user_id}}">
                <div class="form-group">
                    <textarea class="aiz-text-editor" name="reply" data-buttons='[["font", ["bold", "underline", "italic"]],["para", ["ul", "ol"]],["view", ["undo","redo"]]]' required></textarea>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="attachments[]" id="attachments" accept="image/*" multiple>
                            <label class="custom-file-label" for="attachments">{{ translate('Choose file') }}</label>
                        </div>
                        <div class="attachments-preview-container mt-3 d-flex flex-wrap">
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-sm btn-primary" onclick="submit_reply('pending')">{{ translate('Send Reply') }}</button>
                </div>
            </form>
            <div class="pad-top">
                <ul class="list-group list-group-flush">
                    @foreach($ticket->ticketreplies as $ticketreply)
                        <li class="list-group-item px-0">
                            <div class="media">
                                <a class="media-left" href="#">
                                    @if($ticketreply->user->avatar_original != null)
                                        <span class="avatar avatar-sm mr-3">
                                            <img src="{{ uploaded_asset($ticketreply->user->avatar_original) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                        </span>
                                    @else
                                        <span class="avatar avatar-sm mr-3">
                                            <img src="{{ static_asset('assets/img/avatar-place.png') }}">
                                        </span>
                                    @endif
                                </a>
                                <div class="media-body">
                                    <div class="comment-header">
                                        <span class="text-bold h6 text-muted">{{ $ticketreply->user->name ?? translate('Unknown User') }}</span>
                                        <p class="text-muted text-sm fs-11">{{$ticketreply->created_at}}</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                @php echo $ticketreply->reply; @endphp
                                <br>
                                @if($ticketreply->files)
                                    @foreach (explode(",", $ticketreply->files) as $file)
                                        @php $file_detail = \App\Models\Upload::find($file); @endphp
                                        @if($file_detail)
                                            <a href="{{ uploaded_asset($file) }}" download="" class="badge badge-lg badge-inline badge-light mb-1 border mr-1">
                                                <i class="las la-download text-muted mr-1"></i>
                                                {{ $file_detail->file_original_name }}.{{ $file_detail->extension }}
                                            </a>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </li>
                    @endforeach
                    <li class="list-group-item px-0">
                        <div class="media">
                            <a class="media-left" href="#">
                                @if($ticket->user && $ticket->user->avatar_original != null)
                                    <span class="avatar avatar-sm mr-3">
                                        <img src="{{ uploaded_asset($ticket->user->avatar_original) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                    </span>
                                @else
                                    <span class="avatar avatar-sm mr-3">
                                        <img src="{{ static_asset('assets/img/avatar-place.png') }}">
                                    </span>
                                @endif
                            </a>
                            <div class="media-body">
                                <div class="comment-header">
                                    <span class="text-bold h6 text-muted">{{ $ticket->user->name ?? translate('Unknown User') }}</span>
                                    <p class="text-muted text-sm fs-11">{{ $ticket->created_at }}</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            @php echo $ticket->details; @endphp
                            <br>
                            @if($ticket->files)
                                @foreach (explode(",", $ticket->files) as $file)
                                    @php $file_detail = \App\Models\Upload::find($file); @endphp
                                    @if($file_detail)
                                        <a href="{{ uploaded_asset($file) }}" download="" class="badge badge-lg badge-inline badge-light mb-1 border mr-1">
                                            <i class="las la-download text-muted mr-1"></i>
                                            {{ $file_detail->file_original_name }}.{{ $file_detail->extension }}
                                        </a>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </li>
                </ul>
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
                
                var container = input.closest('.col-md-12').find('.attachments-preview-container');
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
