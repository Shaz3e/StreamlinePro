@extends('components.layouts.app')

@section('content')
    @include('partials.page-header', [
        'title' => 'Edit Ticket',
        'breadcrumbs' => [
            ['text' => 'Dashboard', 'link' => route('admin.dashboard')],
            ['text' => 'Support Ticket List', 'link' => route('admin.support-tickets.index')],
            ['text' => 'Edit', 'link' => null],
        ],
    ])

    {{-- Create Form --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <form action="{{ route('admin.support-tickets.update', $supportTicket->id) }}" method="POST"
                    class="needs-validation" novalidate enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="title">Subject</label>
                                    <input type="text" id="title" name="title" class="form-control"
                                        value="{{ old('title', $supportTicket->title) }}" required>
                                </div>
                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- /.col --}}
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="is_internal">Internal Ticket</label>
                                    <select id="is_internal" name="is_internal" class="form-control">
                                        <option value="0"
                                            {{ old('is_internal', $supportTicket->is_internal) == 0 ? 'selected' : '' }}>No
                                        </option>
                                        <option value="1"
                                            {{ old('is_internal', $supportTicket->is_internal) == 1 ? 'selected' : '' }}>Yes
                                        </option>
                                    </select>
                                </div>
                                @error('is_internal')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- /.col --}}
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="admin_id">Assign Ticket</label>
                                    <select id="admin_id" name="admin_id" class="form-control">
                                        <option value="">Select</option>
                                        @foreach ($staffList as $staff)
                                            <option value="{{ $staff->id }}"
                                                {{ old('admin_id', $supportTicket->admin_id) == $staff->id ? 'selected' : '' }}>
                                                {{ $staff->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('admin_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- /.col --}}
                        </div>
                        {{-- /.row --}}

                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="message">Ticket Details</label>
                                    <textarea id="message" name="message" class="form-control" required>{{ old('message', $supportTicket->message) }}</textarea>
                                </div>
                                @error('message')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        {{-- /.row --}}

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="user_id">Select Client</label>
                                    <select id="user_id" name="user_id" class="form-control">
                                        <option value="">Select</option>
                                        @foreach ($clients as $client)
                                            <option
                                                value="{{ $client->id }} {{ old('user_id', $supportTicket->user_id) == $client->id ? 'selected' : '' }}">
                                                {{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('user_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- /.col --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="department_id">Select Department</label>
                                    <select id="department_id" name="department_id" class="form-control">
                                        <option value="">Select</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ old('department_id', $supportTicket->department_id) == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('department_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- /.col --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="support_ticket_status_id">Ticket Status</label>
                                    <select id="support_ticket_status_id" name="support_ticket_status_id"
                                        class="form-control" required>
                                        <option value="">Select</option>
                                        @foreach ($ticketStatuses as $status)
                                            <option value="{{ $status->id }}"
                                                {{ old('support_ticket_status_id', $supportTicket->support_ticket_status_id) == $status->id ? 'selected' : '' }}>
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('support_ticket_status_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- /.col --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="support_ticket_priority_id">Ticket Priority</label>
                                    <select id="support_ticket_priority_id" name="support_ticket_priority_id"
                                        class="form-control" required>
                                        <option value="">Select</option>
                                        @foreach ($ticketPriorities as $priority)
                                            <option value="{{ $priority->id }}"
                                                {{ old('support_ticket_priority_id', $supportTicket->support_ticket_priority_id) == $priority->id ? 'selected' : '' }}>
                                                {{ $priority->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('support_ticket_priority_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- /.col --}}
                        </div>
                        {{-- /.row --}}

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="attachments">Attachments (optional)</label>
                                    <div class="input-group">
                                        <input type="file" name="attachments[]" id="attachments" class="form-control"
                                            multiple>
                                    </div>
                                    <small class="d-block text-muted">Only JPG, PNG with max 2MB file size allowed.</small>
                                </div>
                                @error('attachments')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12">
                                <div id="upload-progress"></div>
                                <div class="progress">
                                    <div id="progress-bar" class="progress-bar" role="progressbar" aria-valuenow="0"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                        <span>0%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- /.row --}}

                    </div>
                    {{-- /.card-body --}}
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success waves-effect waves-light">
                            <i class="ri-save-line align-middle me-2"></i> Update Ticket
                        </button>
                    </div>
                    {{-- /.card-footer --}}
                </form>
            </div>
            {{-- /.card --}}
        </div>
        {{-- /.col --}}
    </div>
    {{-- /.row --}}
@endsection

@push('styles')
@endpush


@push('scripts')
    <script>
        $('#attachments').change(function() {
            var file = this.files[0];
            var formData = new FormData();
            formData.append('attachments', file);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            $.ajax({
                url: `{{ route('admin.support-tickets.upload-attachments') }}`,
                method: 'POST',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var progress = Math.round(evt.loaded / evt.total * 100);
                            console.log(progress);
                            $('#progress-bar').css('width', progress + '%');
                            $('#upload-progress').html(''); // Clear previous message
                            // disable submitButton button wile uploading
                            $('#submitButton').prop('disabled', true);
                        }
                    }, false);
                    return xhr;
                },
                success: function(response) {
                    console.log(response);
                    $('#progress-bar').css('width', '0%'); // Hide progress bar
                    // hide upload successfull after 1 second
                    $('#upload-progress').html('Upload successful!');
                    setTimeout(function() {
                        $('#upload-progress').html('');
                    }, 1000);
                    // enable submitButton when upload finishes
                    $('#submitButton').prop('disabled', false);
                },
                error: function(error) {
                    console.error(error);
                    $('#upload-progress').html('Upload failed!');
                }
            });
        });
    </script>
@endpush