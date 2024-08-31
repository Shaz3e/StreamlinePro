<div>
    <div class="row mb-3">
        <div class="col-md-1 col-sm-12 mb-2">
            <select wire:model.live="perPage" class="form-control form-control-sm form-control-border">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
        {{-- /.col --}}
        <div class="col-md-3 col-sm-12 mb-2">
            <input type="search" wire:model.live="search" class="form-control form-control-sm" placeholder="Search...">
        </div>
        {{-- .col --}}
        <div class="col-md-2 col-sm-12 mb-2">
            <select wire:model.live="showActive" class="form-control form-control-sm form-control-border">
                <option value="" selected="selected">Filters</option>
                <option value="true">Show Active Promotion</option>
                <option value="">Show Inactvie Promotion</option>
            </select>
        </div>
        {{-- /.col --}}
        <div class="col-md-2 col-sm-12 mb-2">
            <select wire:model.live="showFeatured" class="form-control form-control-sm form-control-border">
                <option value="" selected="selected">Filters</option>
                <option value="true">Show Featured Promotion</option>
                <option value="">Hide Featured Promotion</option>
            </select>
        </div>
        {{-- /.col --}}
        <div class="col-md-2 col-sm-12 mb-2">
            <div class="d-grid">
                <a href="{{ route('admin.promotions.create') }}"
                    class="btn btn-success btn-sm waves-effect waves-light">
                    <i class="ri-add-fill align-middle me-2"></i> Create
                </a>
            </div>
        </div>
        {{-- /.col --}}
        <div class="col-md-2 col-sm-12 mb-2">
            <select wire:model.live="showDeleted" class="form-control form-control-sm form-control-border">
                <option value="" selected="selected">Filters</option>
                <option value="">Show Active Record</option>
                <option value="true">Show Deleted Record</option>
            </select>
        </div>
        {{-- /.col --}}
    </div>
    {{-- /.row --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 35%">Name</th>
                                    <th style="30%">Image</th>
                                    <th style="10%">Featured</th>
                                    @if (!$showDeleted)
                                        <th style="10%">Status</th>
                                    @endif
                                    <th style="width: 15%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalRecords = $promotions->total();
                                    $currentPage = $promotions->currentPage();
                                    $perPage = $promotions->perPage();
                                    $id = $totalRecords - ($currentPage - 1) * $perPage;
                                @endphp
                                @foreach ($promotions as $promotion)
                                    <tr wire:key="{{ $promotion->id }}">
                                        <td>{{ $id-- }}</td>
                                        <td>{{ $promotion->name }}</td>
                                        <td>
                                            <a class="promotion-image-lightbox"
                                                href="{{ asset('storage/' . $promotion->image) }}">
                                                <img src="{{ asset('storage/' . $promotion->image) }}"
                                                    class="w-auto rounded" style="max-height: 50px" loading="lazy"
                                                    decoding="async" />
                                            </a>
                                        </td>
                                        <td>
                                            <input type="checkbox" wire:change="toggleFeatured({{ $promotion->id }})"
                                                id="is_featured_{{ $promotion->id }}" switch="bool"
                                                {{ $promotion->is_featured ? 'checked' : '' }} />
                                            <label for="is_featured_{{ $promotion->id }}" data-on-label="Yes"
                                                data-off-label="No"></label>
                                        </td>
                                        @if (!$showDeleted)
                                            <td>
                                                <input type="checkbox" wire:change="toggleStatus({{ $promotion->id }})"
                                                    id="is_active_{{ $promotion->id }}" switch="bool"
                                                    {{ $promotion->is_active ? 'checked' : '' }} />
                                                <label for="is_active_{{ $promotion->id }}" data-on-label="Yes"
                                                    data-off-label="No"></label>
                                            </td>
                                        @endif
                                        <td class="text-right">
                                            @if ($showDeleted)
                                                @can('promotion.restore')
                                                    <button wire:click="confirmRestore({{ $promotion->id }})"
                                                        class="btn btn-sm btn-outline-info" data-toggle="modal"
                                                        data-target="#deleteModal">
                                                        <i class="ri-arrow-go-back-line"></i>
                                                    </button>
                                                @endcan
                                                @can('promotion.force.delete')
                                                    <button wire:click="confirmForceDelete({{ $promotion->id }})"
                                                        class="btn btn-sm btn-outline-danger" data-toggle="modal"
                                                        data-target="#deleteModal">
                                                        <i class="ri-delete-bin-7-line"></i>
                                                    </button>
                                                @endcan
                                            @else
                                                @can('promotion.read')
                                                    <a href="{{ route('admin.promotions.show', $promotion->id) }}"
                                                        class="btn btn-sm btn-outline-info">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                @endcan
                                                @can('promotion.update')
                                                    <a href="{{ route('admin.promotions.edit', $promotion->id) }}"
                                                        class="btn btn-sm btn-outline-success">
                                                        <i class="ri-pencil-line"></i>
                                                    </a>
                                                @endcan
                                                @can('promotion.delete')
                                                    <button wire:click="confirmDelete({{ $promotion->id }})"
                                                        class="btn btn-sm btn-outline-danger" data-toggle="modal"
                                                        data-target="#deleteModal">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                @endcan
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $promotions->links() }}
                </div>
            </div>
        </div>
        {{-- /.col --}}
    </div>
    {{-- /.row --}}
</div>
@push('styles')
@endpush

@push('scripts')
@endpush
@script
    <script>
        // Status Changed
        document.addEventListener('statusChanged', () => {
            Toast.fire({
                icon: 'success',
                title: "Status has been updated successfully",
            })
        })

        // Error
        document.addEventListener('error', () => {
            Toast.fire({
                icon: 'error',
                title: "Record not found",
            })
        })

        // Show Delete Confirmation
        document.addEventListener('showDeleteConfirmation', () => {
            Swal.fire({
                title: "Are you sure?",
                text: "You want to delete this record!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.dispatch('deleteConfirmed');
                    Swal.fire({
                        title: "Deleted!",
                        text: "The record has been deleted.",
                        icon: "success"
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                        'Cancelled',
                        'This record is safe :)',
                        'error'
                    );
                }
            });
        })

        // Show Restore Confirmation
        document.addEventListener('confirmRestore', () => {
            Swal.fire({
                title: "Are you sure?",
                text: "You want to restore this record!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, restore it!",
                cancelButtonText: "No, cancel!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.dispatch('restored');
                    Swal.fire({
                        title: "Restored!",
                        text: "The record has been restored.",
                        icon: "success"
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                        'Cancelled',
                        'This record is still deleted :)',
                        'error'
                    );
                }
            });
        })

        // Show Force Delete Confirmation
        document.addEventListener('confirmForceDelete', () => {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.dispatch('forceDeleted');
                    Swal.fire({
                        title: "Deleted!",
                        text: "The record has been deleted.",
                        icon: "success"
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                        'Cancelled',
                        'This record is deleted but can be restore later :)',
                        'error'
                    );
                }
            });
        })
    </script>
@endscript
