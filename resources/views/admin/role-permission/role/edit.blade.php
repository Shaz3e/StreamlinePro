@extends('components.layouts.app')

@section('content')
    @include('partials.page-header', [
        'title' => 'Create New Role',
        'breadcrumbs' => [
            ['text' => 'Dashboard', 'link' => route('admin.dashboard')],
            ['text' => 'Roles', 'link' => route('admin.roles-permissions.roles.index')],
            ['text' => 'Create', 'link' => null],
        ],
    ])

    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('admin.roles-permissions.roles.update', $role->id) }}" method="POST">
                @csrf
                @method('put')
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Role Name</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $role->name) }}">
                                </div>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        {{-- /.row --}}
                    </div>
                    {{-- /.card-body --}}
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success waves-effect waves-light">
                            <i class="ri-save-line align-middle me-2"></i> Update
                        </button>
                    </div>
                    {{-- /.card-footer --}}
                </div>
                {{-- /.card --}}
            </form>
        </div>
        {{-- /.col --}}
    </div>
    {{-- /.row --}}

    {{-- Role's Permissions --}}
    <div class="row">
        <div class="col-md-">
            <div class="card">
                <div class="card-header">Permissions</div>
                <form action="{{ route('admin.roles-permissions.roles.update', $role->id) }}" method="POST">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Permission Name</th>
                                    <th>All</th>
                                    <th>List View</th>
                                    <th>Create</th>
                                    <th>Read</th>
                                    <th>Update</th>
                                    <th>Delete</th>
                                    <th>Restore</th>
                                    <th>Force Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions->groupBy(function ($permission) {
                                        return explode('.', $permission->name)[0];
                                    }) as $modelName => $modelPermissions)
                                    <tr>
                                        <td>{{ $modelName }}</td>
                                        <td><input type="checkbox"></td>
                                        @foreach (['list', 'create', 'read', 'update', 'delete', 'restore', 'force.delete'] as $action)
                                            <td>
                                                @if (
                                                    $modelPermissions->contains(function ($permission) use ($action) {
                                                        return str_contains($permission->name, $action);
                                                    }))
                                                    <input type="checkbox" name="permissions[]" class="form-checkbox-input"
                                                        id="checkPermission-{{ $modelPermissions->first(function ($permission) use ($action) {
                                                            return str_contains($permission->name, $action);
                                                        })->id }}"
                                                        value="{{ $modelPermissions->first(function ($permission) use ($action) {
                                                            return str_contains($permission->name, $action);
                                                        })->name }}"
                                                        {{ in_array(
                                                            $modelPermissions->first(function ($permission) use ($action) {
                                                                return str_contains($permission->name, $action);
                                                            })->id,
                                                            $rolePermissions,
                                                        )
                                                            ? 'checked'
                                                            : '' }} />
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- /.card-body --}}
                    <div class="card-footer">
                        <button type="submit" name="syncPermissions" class="btn btn-success waves-effect waves-light">
                            <i class="ri-save-line align-middle me-2"></i> Add/Remove Permissions
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
        // Select all checkboxes in a row when "All" is clicked
        $('tbody tr td:nth-child(2) input[type="checkbox"]').on('click', function() {
            var row = $(this).closest('tr');
            row.find('td input[type="checkbox"]').prop('checked', $(this).is(':checked'));
        });

        // Check "Delete" and "Restore" when "Force Delete" is clicked
        $('tbody tr td:nth-child(9) input[type="checkbox"]').on('click', function() {
            var row = $(this).closest('tr');
            if ($(this).is(':checked')) {
                row.find('td:nth-child(7) input[type="checkbox"]').prop('checked', true);
                row.find('td:nth-child(8) input[type="checkbox"]').prop('checked', true);
            }
        });
    </script>
@endpush