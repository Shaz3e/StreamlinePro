@extends('components.layouts.app')

@section('content')
    @include('partials.page-header', [
        'title' => 'Settings',
        'breadcrumbs' => [
            ['text' => 'Dashboard', 'link' => route('admin.dashboard')],
            ['text' => 'View Settings', 'link' => null],
        ],
    ])

    <div class="row">
        <div class="col-12">

            @include('admin.app-setting.sidebar')

            {{-- Right Sidebar --}}
            <div class="email-rightbar mb-3">

                @if (request()->routeIs('admin.settings.general'))
                    @include('admin.app-setting.general.general')
                @endif
                
                @if (request()->routeIs('admin.settings.authentication'))
                    @include('admin.app-setting.authentication.authentication')
                @endif

            </div>
            {{-- /.email-rightbar --}}

        </div>
        {{-- /.col --}}

    </div>
    {{-- /.row --}}

@endsection

@push('styles')
@endpush

@push('scripts')
@endpush