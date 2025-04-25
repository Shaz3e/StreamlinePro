@extends('components.layouts.app')

@section('content')
    @include('partials.page-header', [
        'title' => 'View Download',
        'breadcrumbs' => [
            ['text' => 'Dashboard', 'link' => route('admin.dashboard')],
            ['text' => 'Downloads', 'link' => route('admin.downloads.index')],
            ['text' => 'View Company', 'link' => null],
        ],
    ])

    {{-- Show Record --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Download Details
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td>Title</td>
                            <td>{{ $download->title }}</td>
                        </tr>
                        <tr>
                            <td>Version</td>
                            <td>{{ $download->version }}</td>
                        </tr>
                        <tr>
                            <td>External Download Link</td>
                            <td>
                                @if ($download->download_link)
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="download_link"
                                            value="{{ $download->download_link }}" readonly>
                                        <button class="btn btn-sm btn-flat btn-success"
                                            onclick="copyToClipboard('download_link')">Click to Copy</button>
                                    </div>

                                    <!-- Bootstrap alert for showing the success message -->
                                    <div class="alert alert-success alert-dismissible fade show d-none" id="copyAlert"
                                        role="alert">
                                        <strong>Success!</strong> The link has been copied to your clipboard.
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @else
                                    <span class="badge bg-danger">Not Available</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Download File</td>
                            <td>
                                @if ($download->file_path)
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="file_path"
                                        value="{{ config('app.url') . '/' . $download->file_path }}" readonly>
                                    <button class="btn btn-sm btn-flat btn-success"
                                        onclick="copyToClipboard('file_path')">Click to Copy</button>
                                </div>

                                <!-- Bootstrap alert for showing the success message -->
                                <div class="alert alert-success alert-dismissible fade show d-none" id="copyAlert"
                                    role="alert">
                                    <strong>Success!</strong> The link has been copied to your clipboard.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>

                                @else
                                    <span class="badge bg-danger">Not Available</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>
                                @if ($download->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Created On</td>
                            <td>{{ $download->created_at->format('l, F j, Y') }}</td>
                        </tr>
                        <tr>
                            <td>Updated On</td>
                            <td>{{ $download->updated_at->format('l, F j, Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            {{-- /.card --}}
        </div>
        {{-- /.col --}}
    </div>
    {{-- /.row --}}

    {{-- Show Related Staff --}}
    @if ($download->users()->count() == 0)
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info">
                    No Client assigned to this download.
                </div>
            </div>
            {{-- /.col --}}
        </div>
        {{-- /.row --}}
    @else
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        Client List
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="table">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($download->users as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->mobile }}</td>
                                                <td class="text-end">
                                                    <a href="{{ route('admin.users.show', $user->id) }}"
                                                        class="btn btn-primary btn-sm waves-effect waves-light">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- /.table-responsive --}}
                    </div>
                    {{-- /.card-body --}}
                </div>
                {{-- /.card --}}
            </div>
            {{-- /.col --}}
        </div>
        {{-- /.row --}}
    @endif
@endsection

@push('styles')
@endpush

@push('scripts')
    <script>
        function copyToClipboard(inputId) {
            // Get the input field element dynamically based on the inputId passed
            var copyText = document.getElementById(inputId);

            // Select the text in the input field
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text to clipboard
            document.execCommand("copy");

            // Show the Bootstrap alert by removing the "d-none" class
            var alertBox = document.getElementById("copyAlert");
            alertBox.classList.remove("d-none");

            // Optionally, hide the alert after a few seconds
            setTimeout(function() {
                alertBox.classList.add("d-none");
            }, 3600); // Hide after 3 seconds
        }
    </script>
@endpush
