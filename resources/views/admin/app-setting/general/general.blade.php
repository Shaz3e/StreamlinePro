<form action="{{ route('admin.settings.general.store') }}" method="POST" class="needs-validation" novalidate
    enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-6">
                    <div class="form-group">
                        <label for="site_name">Site Name</label>
                        <input type="text" name="site_name" id="site_name" class="form-control"
                            value="{{ old('site_name', DiligentCreators('site_name')) }}" required>
                    </div>
                    @error('site_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                {{-- /.col --}}
                <div class="col-6">
                    <div class="form-group">
                        <label for="site_timezone">Site Timezone</label>
                        {{ getAllTimeZonesSelectBox(old('site_timezone', DiligentCreators('site_timezone'))) }}
                    </div>
                    @error('site_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                {{-- /.col --}}
            </div>
            {{-- /.row --}}
            <div class="row mb-3">
                <div class="col-6">
                    <div class="form-group">
                        <label for="site_logo_small">Logo <small>Small</small></label>
                        <input type="file" name="site_logo_small" id="site_logo_small" class="form-control"
                            value="{{ old('site_logo_small') }}">
                    </div>
                    <div><span class="text-muted">Best size 24x24 px</span></div>
                    @error('site_logo_small')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                {{-- /.col --}}
                <div class="col-6 mt-4">
                    <img src="{{ asset('storage/' . DiligentCreators('site_logo_small')) }}">
                </div>
            </div>
            {{-- /.row --}}
            <div class="row mb-3">
                <div class="col-6">
                    <div class="form-group">
                        <label for="site_logo_light">Logo <small>light</small></label>
                        <input type="file" name="site_logo_light" id="site_logo_light" class="form-control"
                            value="{{ old('site_logo_light') }}">
                    </div>
                    <div><span class="text-muted">Best size 137x30 px</span></div>
                    @error('site_logo_light')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                {{-- /.col --}}
                <div class="col-6 mt-4">
                    <img src="{{ asset('storage/' . DiligentCreators('site_logo_light')) }}">
                </div>
            </div>
            {{-- /.row --}}
            <div class="row mb-3">
                <div class="col-6">
                    <div class="form-group">
                        <label for="site_logo_dark">Logo <small>dark</small></label>
                        <input type="file" name="site_logo_dark" id="site_logo_dark" class="form-control"
                            value="{{ old('site_logo_dark') }}">
                    </div>
                    <div><span class="text-muted">Best size 137x30 px</span></div>
                    @error('site_logo_dark')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                {{-- /.col --}}

                <div class="col-6 mt-4">
                    <img src="{{ asset('storage/' . DiligentCreators('site_logo_dark')) }}">
                </div>
            </div>
            {{-- /.row --}}
            <div class="row mb-3">
                <div class="col-6">
                    <div class="form-group">
                        <label for="site_url">Site URL</label>
                        <input type="url" name="site_url" id="site_url" class="form-control"
                            value="{{ old('site_url', DiligentCreators('site_url')) }}" required>
                    </div>
                    @error('site_url')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                {{-- /.col --}}
                <div class="col-6">
                    <div class="form-group">
                        <label for="app_url">Application URL</label>
                        <input type="url" name="app_url" id="app_url" class="form-control"
                            value="{{ old('app_url', DiligentCreators('app_url')) }}" required>
                    </div>
                    @error('app_url')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                {{-- /.col --}}
            </div>
            {{-- /.row --}}
        </div>
        {{-- /.card-body --}}
        <div class="card-footer">
            <x-form.button text="Save General Setting" />
        </div>
        {{-- /.card-footer --}}
    </div>
    {{-- /.card --}}
</form>

@push('styles')
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('scripts')
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".select2").select2();
        });
    </script>
@endpush