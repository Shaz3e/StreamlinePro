@extends('components.layouts.app')

@section('content')
    @include('partials.page-header', [
        'title' => 'View Knowledgebase Article',
        'breadcrumbs' => [
            ['text' => 'Dashboard', 'link' => route('admin.dashboard')],
            ['text' => 'View Article List', 'link' => route('admin.knowledgebase.articles.index')],
            ['text' => 'View', 'link' => null],
        ],
    ])

    {{-- Show Record --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $article->title }}</h4>
                    <h6 class="card-subtitle font-14 text-muted">
                        <span id="route-to-copy">{{ route('knowledgebase.article', $article->slug) }}</span>
                        <button class="btn btn-sm btn-success" onclick="copyToClipboard()">Click to Copy</button>
                    </h6>
                </div>
                {{-- /.card-body --}}
                @if (!is_null($article->featured_image))
                    <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->slug }}"
                        class="img-fluid" alt="{{ $article->title }}">
                @endif
                <div class="card-body">
                    <div class="card-text">
                        {!! $article->content !!}
                    </div>
                </div>
                {{-- /.card-body --}}
                <div class="card-footer">
                    <div class="row">
                        <div class="col-6">
                            <p>
                                Listed in
                                @if ($article->category)
                                    <a href="{{ route('admin.knowledgebase.categories.show', $article->category_id) }}"
                                        class="card-link">{{ $article->category->name }}</a>
                                @else
                                    <a href="#" class="card-link">Uncategorized</a>
                                @endif
                                @foreach ($article->products as $product)
                                    <span class="badge bg-success">{{ $product->name }}</span>
                                @endforeach
                            </p>
                            <p>
                                Created By <a href="{{ route('admin.users.show', $article->author_id) }}"
                                    class="card-link">{{ $article->author->name }}</a>
                            </p>

                        </div>
                        {{-- /.col --}}
                        <div class="col-6 text-end">
                            <p>Created at {{ $article->created_at->format('F j, Y, g:i A') }}</p>
                            <p>Modified at {{ $article->updated_at->format('F j, Y, g:i A') }}</p>
                        </div>
                        {{-- /.col --}}
                    </div>
                    {{-- /.row --}}
                </div>
                {{-- /.card-footer --}}
            </div>
            {{-- /.card --}}
        </div>
        {{-- /.col --}}
    </div>
    {{-- /.row --}}

    {{-- Show Audit History --}}
    @hasrole('superadmin')
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
    @endhasrole
@endsection

@push('styles')
    <style>
        img {
            max-width: 100%;
            height: auto;
        }
    </style>
@endpush

@push('scripts')
    @hasrole('superadmin')
        <script>
            $(document).ready(function() {
                // Audit Log Show Modal
                $('.show-audit-modal').click(function(e) {
                    e.preventDefault();
                    const userId = $(this).data('audit-id');
                    // Fetch details via AJAX
                    $.ajax({
                        url: `{{ route('admin.users.audit', ':id') }}`.replace(':id', userId),
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
                    const userId = $(this).data('audit-id');

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
                                url: `{{ route('admin.users.audit.delete', ':id') }}`.replace(
                                    ':id', userId),
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
    @endhasrole

    <script>
        function copyToClipboard() {
            // Get the text to copy
            const textToCopy = document.getElementById('route-to-copy').textContent;

            if (!navigator.clipboard) {
                // Fallback for browsers that don't support navigator.clipboard
                const textArea = document.createElement('textarea');
                textArea.value = textToCopy;
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand('copy');
                    alert('Route copied to clipboard!');
                } catch (err) {
                    console.error('Failed to copy: ', err);
                }
                document.body.removeChild(textArea);
            } else {
                // Copy the text to the clipboard
                navigator.clipboard.writeText(textToCopy)
                    .then(() => {
                        alert('Route copied to clipboard!');
                    })
                    .catch(err => {
                        console.error('Failed to copy: ', err);
                    });
            }
        }
    </script>
@endpush
