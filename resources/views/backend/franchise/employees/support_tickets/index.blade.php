@extends('backend.franchise.employees.layout')

@section('panel_content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Support Tickets') }}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newTicketModal">
                <i class="las la-plus mr-1"></i>{{ translate('New Ticket') }}
            </button>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="aiz-table" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>{{ translate('Ticket Code') }}</th>
                    <th>{{ translate('Subject') }}</th>
                    <th>{{ translate('Status') }}</th>
                    <th>{{ translate('Date') }}</th>
                    <th class="text-right">{{ translate('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tickets as $ticket)
                <tr>
                    <td>#{{ $ticket->code }}</td>
                    <td>
                        {{ $ticket->subject }}
                        @if($ticket->files)
                            <i class="las la-paperclip ml-2 text-muted" title="{{ translate('Has attachments') }}"></i>
                        @endif
                    </td>
                    <td>
                        @if($ticket->status == 'pending')
                            <span class="badge badge-danger">{{ translate('Pending') }}</span>
                        @elseif($ticket->status == 'open')
                            <span class="badge badge-secondary">{{ translate('Open') }}</span>
                        @else
                            <span class="badge badge-success">{{ translate('Solved') }}</span>
                        @endif
                    </td>
                    <td>{{ $ticket->created_at }}</td>
                    <td class="text-right">
                        <a href="{{ route('franchise.employee.support_tickets.show', encrypt($ticket->id)) }}" class="btn btn-soft-primary btn-sm btn-icon btn-circle">
                            <i class="las la-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
                @if($tickets->count() == 0)
                <tr>
                    <td colspan="5" class="text-center text-muted">{{ translate('No tickets found') }}</td>
                </tr>
                @endif
            </tbody>
        </table>
        <div class="clearfix mt-3">
            <div class="pull-right">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
</div>

<!-- New Ticket Modal -->
<div class="modal fade" id="newTicketModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('New Support Ticket') }}</h5>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{ route('franchise.employee.support_tickets.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ translate('Subject') }} <span class="text-danger">*</span></label>
                        <input type="text" name="subject" class="form-control" required placeholder="{{ translate('Enter subject') }}">
                    </div>
                    <div class="form-group">
                        <label>{{ translate('Details') }} <span class="text-danger">*</span></label>
                        <textarea name="details" class="form-control" rows="5" required placeholder="{{ translate('Describe your issue in detail...') }}"></textarea>
                    </div>
                    <div class="form-group">
                        <label>{{ translate('Attachments') }}</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="attachments[]" id="attachments" accept="image/*" multiple>
                            <label class="custom-file-label" for="attachments">{{ translate('Choose file') }}</label>
                        </div>
                        <div class="attachments-preview-container mt-3 d-flex flex-wrap">
                        </div>
                        <small class="text-muted">{{ translate('You can upload images, archives (zip/rar) or documents.') }}</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ translate('Submit Ticket') }}</button>
                </div>
            </form>
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
