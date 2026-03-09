@extends('franchise.layouts.app')

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Support Ticket') }}</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 fas-dash-cards">
            <div class="p-4 mb-3 c-pointer text-center bg-white shadow-sm rounded hov-shadow-lg has-transition border" data-toggle="modal" data-target="#ticket_modal">
                <span class="d-block mb-3">
                    <i class="las la-plus la-2x text-primary"></i>
                </span>
                <span class="d-block b fs-16 fw-600 text-primary">{{ translate('Create a Ticket') }}</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Tickets') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th data-breakpoints="lg">{{ translate('Code') }}</th>
                        <th data-breakpoints="lg">{{ translate('Sending Date') }}</th>
                        <th>{{ translate('Subject') }}</th>
                        <th>{{ translate('Status') }}</th>
                        <th class="text-right">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tickets as $key => $ticket)
                        <tr>
                            <td>#{{ $ticket->code }}</td>
                            <td>{{ $ticket->created_at }}</td>
                            <td>
                                {{ $ticket->subject }}
                                @if($ticket->files)
                                    <i class="las la-paperclip ml-2 text-muted" title="{{ translate('Has attachments') }}"></i>
                                @endif
                            </td>
                            <td>
                                @if ($ticket->status == 'pending')
                                    <span class="badge badge-inline badge-danger">{{ translate('Pending') }}</span>
                                @elseif ($ticket->status == 'open')
                                    <span class="badge badge-inline badge-secondary">{{ translate('Open') }}</span>
                                @else
                                    <span class="badge badge-inline badge-success">{{ translate('Solved') }}</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('franchise.support_tickets.show', encrypt($ticket->id)) }}" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('View Details') }}">
                                    <i class="las la-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <div class="modal fade" id="ticket_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Create a Ticket') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="" action="{{ route('franchise.support_tickets.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ translate('Subject') }} <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="subject" placeholder="{{ translate('Subject') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ translate('Details') }} <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="details" rows="8" placeholder="{{ translate('Type your reply') }}" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ translate('Attachments') }}</label>
                            <div class="col-sm-9">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="attachments[]" id="attachments" accept="image/*" multiple>
                                    <label class="custom-file-label" for="attachments">{{ translate('Choose file') }}</label>
                                </div>
                                <div class="attachments-preview-container mt-3 d-flex flex-wrap">
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('Cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ translate('Send Ticket') }}</button>
                        </div>
                    </form>
                </div>
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
                
                var container = input.closest('.col-sm-9').find('.attachments-preview-container');
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
