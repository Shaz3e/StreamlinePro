@extends('components.layouts.app')

@section('content')
    @include('partials.page-header', [
        'title' => 'My Downloads',
        'breadcrumbs' => [
            ['text' => 'Dashboard', 'link' => route('dashboard')],
            ['text' => 'Available Downloads List', 'link' => null],
        ],
    ])

    <div class="row">
        @if ($downloads->count() > 0)
            @foreach ($downloads as $download)
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">{{ $download->title }}
                                <small class="text-muted">{{ $download->version }}</small>
                            </h4>
                            <p class="card-text">
                                {!! shortTextWithOutHtml($download->description, 50) !!}
                            </p>
                            <p class="card-text">
                                @if ($download->file_path)
                                    <a href="{{ asset('storage/' . $download->file_path) }}"
                                        class="btn btn-sm btn-flat btn-success">
                                        <i class="mdi mdi-download"></i> Download
                                    </a>
                                @endif
                                @if ($download->download_link)
                                    <a href="{{ $download->download_link }}" target="_blank"
                                        class="btn btn-sm btn-flat btn-success">
                                        <i class="mdi mdi-download"></i> Download
                                    </a>
                                @endif
                                <a href="{{ route('downloads.show', $download->id) }}" class="btn btn-sm btn-flat btn-info">
                                    <i class="mdi mdi-eye"></i> View Details
                                </a>
                            </p>
                            <p class="card-text">
                                <small class="text-muted">Last updated {{ $download->updated_at->diffForHumans() }}</small>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-md-12">
                <div class="alert alert-info">
                    <strong>No downoads avilable at this time.</strong>
                </div>
            </div>
        @endif
    </div>
    {{-- /.row --}}
@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
