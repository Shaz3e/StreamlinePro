@extends('components.layouts.app')

@section('content')
    @include('partials.page-header', [
        'title' => 'My Downloads',
        'breadcrumbs' => [
            ['text' => 'Dashboard', 'link' => route('dashboard')],
            ['text' => 'Downloads', 'link' => route('downloads.index')],
            ['text' => 'View Download', 'link' => null],
        ],
    ])

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $download->title }}</h4>
                </div>
                <div class="card-body">
                    {!! $download->description !!}
                </div>
                {{-- /.card-body --}}
                <div class="card-footer">
                    @if ($download->file_path)
                        <a href="{{ asset('storage/' . $download->file_path) }}" class="btn btn-sm btn-flat btn-success">
                            <i class="mdi mdi-download"></i> Download
                        </a>
                    @endif
                    @if ($download->download_link)
                        <a href="{{ $download->download_link }}" target="_blank" class="btn btn-sm btn-flat btn-success">
                            <i class="mdi mdi-download"></i> Download
                        </a>
                    @endif
                    <small class="text-muted">Last updated {{ $download->updated_at->diffForHumans() }}</small>
                </div>
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
@endpush
