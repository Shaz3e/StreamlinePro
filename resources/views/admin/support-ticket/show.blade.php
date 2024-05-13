@extends('components.layouts.app')

@section('content')
    @include('partials.page-header', [
        'title' => 'View Ticket',
        'breadcrumbs' => [
            ['text' => 'Dashboard', 'link' => route('admin.dashboard')],
            ['text' => 'Support Ticket List', 'link' => route('admin.support-tickets.index')],
            ['text' => 'View', 'link' => null],
        ],
    ])

    {{-- Show Ticket Info --}}
    <div class="row">
        <div class="col-9">
            <div class="card" style="height: calc(100% - 15px)">
                <div class="card-body">
                    <div class="row mb-5">
                        <div class="col-md-3">
                            <small>Ticket Number</small><br>
                            <strong>{{ $supportTicket->ticket_number }}</strong>
                        </div>
                        {{-- /.col --}}
                        <div class="col-md-3">
                            <small>User</small><br>
                            <strong>
                                @if ($supportTicket->user_id)
                                    <a href="{{ route('admin.users.show', $supportTicket->user->id) }}">
                                        {{ $supportTicket->user->name }}
                                    </a>
                                @endif
                                {{-- {{ optional($supportTicket->user)->name ? $supportTicket->user->name : 'N/A' }} --}}
                            </strong>
                        </div>
                        {{-- /.col --}}
                        <div class="col-md-3">
                            <small>Status</small><br>
                            <strong class="badge"
                                style="background-color:{{ $supportTicket->status->bg_color }};color:{{ $supportTicket->status->text_color }};">
                                {{ $supportTicket->status->name }}
                            </strong>
                        </div>
                        {{-- /.col --}}
                        <div class="col-md-3">
                            <small>Priority</small><br>
                            <strong class="badge"
                                style="background-color:{{ $supportTicket->priority->bg_color }};color: {{ $supportTicket->priority->text_color }};">
                                {{ $supportTicket->priority->name }}
                            </strong>
                        </div>
                        {{-- /.col --}}
                    </div>
                    {{-- /.row --}}

                    <div class="row">
                        <div class="col-md-3">
                            <small>Created at</small><br>
                            <strong>{{ $supportTicket->created_at->format('l, F j, Y') }}</strong>
                        </div>
                        {{-- /.col --}}
                        <div class="col-md-3">
                            <small>Last Updated at</small><br>
                            <strong>{{ $supportTicket->updated_at->format('l, F j, Y') }}</strong>
                        </div>
                        {{-- /.col --}}
                        <div class="col-md-3">
                            <small>Last Replied By</small><br>
                            @if ($supportTicket->lastReply)
                                <strong>
                                    @if ($supportTicket->lastReply->staff_reply_by)
                                        {{ $supportTicket->lastReply->staff->name }}
                                    @elseif($supportTicket->lastReply->client_reply_by)
                                        {{ $supportTicket->lastReply->client->name }}
                                    @else
                                        N/A
                                    @endif
                                </strong>
                            @endif
                        </div>
                        {{-- /.col --}}
                        <div class="col-md-3">
                            <small>Last Reply at</small><br>
                            <strong>
                                {{ optional($supportTicket->lastReply)->created_at ? $supportTicket->lastReply->created_at->format('l, F j, Y') : 'N/A' }}
                            </strong>
                        </div>
                        {{-- /.col --}}
                    </div>
                    {{-- /.row --}}
                </div>
                {{-- /.card-body --}}

                @can('support-ticket.update')
                    <div class="card-footer">
                        <a href="{{ route('admin.support-tickets.edit', $supportTicket->id) }}">
                            <i class="ri-pencil-line"></i> Edit
                        </a>
                    </div>
                    {{-- /.card-footer --}}
                @endcan
            </div>
            {{-- /.card --}}
        </div>
        {{-- /.col --}}
        <div class="col-3">
            <div class="card" style="height: calc(100% - 15px)">
                <div class="card-body">
                    <small>Internal Ticket</small><br>
                    @if ($supportTicket->is_internal)
                        <strong class="badge bg-success">Yes</strong><br>
                    @else
                        <strong class="badge bg-danger">No</strong><br>
                    @endif
                    @if ($supportTicket->admin)
                        <small>Assigned To</small><br>
                        <strong>{{ $supportTicket->admin->name }}</strong><br>
                    @endif
                    @if ($supportTicket->department)
                        <small>Department</small><br>
                        <strong>{{ $supportTicket->department->name }}</strong>
                    @endif
                </div>
                {{-- /.card-body --}}
            </div>
            {{-- /.card --}}
        </div>
        {{-- /.col --}}
    </div>
    {{-- /.row --}}

    {{-- Show Ticket Details --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Title: {{ $supportTicket->title }}
                    </h3>
                </div>
                {{-- /.card-header --}}
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            {!! $supportTicket->message !!}
                        </div>
                    </div>
                    {{-- /.row --}}

                    @if (!is_null($attachments))
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>Attachments</strong>
                                <ul>
                                    @foreach ($attachments as $attachment)
                                        <li>
                                            <a href="{{ asset('storage/' . $attachment) }}" target="_blank" class="">
                                                Open
                                            </a>
                                            <a href="{{ asset('storage/' . $attachment) }}" download="{{ $attachment }}"
                                                class="">
                                                Download
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        {{-- /.row --}}
                    @endif
                </div>
                {{-- /.card-body --}}
                <div class="card-footer">
                    <button onclick="scrollToReply()" class="btn btn-info btn-sm"><i class="ri-reply-line"></i> Response to
                        Ticket</button>
                </div>
                {{-- /.card-footer --}}
            </div>
            {{-- /.card --}}
        </div>
        {{-- /.col --}}
    </div>
    {{-- /.row --}}

    @include('admin.support-ticket.support-ticket-replies')
    @include('admin.support-ticket.support-ticket-reply')

    {{-- Show Audit History --}}
    @hasanyrole(['superadmin', 'developer'])
        @if (count($audits) > 0)
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Audit History</h4>
                        </div>
                        {{-- /.card-header --}}

                        <div class="card-body">
                            <table class="table" id="#audit-log-table">
                                <thead>
                                    <tr>
                                        <th>Audit</th>
                                        <th>IP</th>
                                        <th>Modified At</th>
                                        <th>Records</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($audits as $audit)
                                        <tr>
                                            <td></td>
                                            <td>{{ $audit->ip_address }}</td>
                                            <td>{{ $audit->created_at }}</td>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-primary btn-sm waves-effect waves-light show-audit-modal"
                                                    data-bs-toggle="modal" data-bs-target=".auditLog"
                                                    data-audit-id="{{ $audit->id }}">
                                                    <i class="ri-history-line"></i>
                                                </button>

                                                <button type="button"
                                                    class="btn btn-danger btn-sm waves-effect waves-light delete-audit-log"
                                                    data-audit-id="{{ $audit->id }}">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{ $audits->links('pagination::bootstrap-5') }}
                        </div>
                        {{-- /.card-body --}}
                    </div>
                    {{-- /.card --}}
                </div>
                {{-- /.col --}}
            </div>
            {{-- /.row --}}
        @endif

        {{-- Audit Log --}}
        <div class="modal fade auditLog" tabindex="-1" aria-labelledby="auditLog" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="auditLog">Audit Log</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    @endhasanyrole
@endsection

@push('styles')
@endpush

@push('scripts')
    <script>
        // Scrolle To Reply
        function scrollToReply() {
            const targetElement = document.getElementById('reply');
            targetElement.scrollIntoView({
                behavior: 'smooth' // Smooth scrolling effect
            });
        }
    </script>
    @hasanyrole(['superadmin', 'developer'])
        <script>
            $(document).ready(function() {
                // Audit Log Show Modal
                $('.show-audit-modal').click(function(e) {
                    e.preventDefault();
                    const supportTicketId = $(this).data('audit-id');
                    // Fetch details via AJAX
                    $.ajax({
                        url: `{{ route('admin.support-tickets.audit', ':id') }}`.replace(':id',
                            supportTicketId),
                        type: 'GET',
                        success: function(data) {
                            // Populate modal content with fetched data
                            $('.auditLog .modal-body').html(data);
                            // Show the modal
                            $('.auditLog').modal('show');
                        },
                        error: function(error) {
                            console.error('Error:', error);
                        }
                    });
                });
                $('.delete-audit-log').click(function(e) {
                    e.preventDefault();
                    const supportTicketId = $(this).data('audit-id');

                    // Show confirmation dialog
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'You will not be able to recover this audit log!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'No, cancel!',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // If user confirms, proceed with deletion
                            $.ajax({
                                url: `{{ route('admin.support-tickets.audit.delete', ':id') }}`
                                    .replace(
                                        ':id', supportTicketId),
                                type: 'GET',
                                success: function(data) {
                                    // Show success message
                                    Swal.fire('Deleted!',
                                        'Your audit log has been deleted.', 'success');
                                    // reload page
                                    location.reload();
                                },
                                error: function(error) {
                                    console.error('Error:', error);
                                    // Show error message if deletion fails
                                    Swal.fire('Error!',
                                        'Failed to delete audit log or it has been deleted',
                                        'error');
                                }
                            });
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            // If user cancels, show message that the history is safe
                            Swal.fire('Cancelled', 'Your audit log is safe :)', 'info');
                        }
                    });
                });
            });
        </script>
    @endhasanyrole
@endpush
