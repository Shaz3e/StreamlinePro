@extends('components.layouts.app')

@section('content')
    @include('partials.page-header', [
        'title' => 'View Invoice',
        'breadcrumbs' => [
            ['text' => 'Dashboard', 'link' => route('admin.dashboard')],
            ['text' => 'Invoice List', 'link' => route('admin.invoices.index')],
            ['text' => 'View', 'link' => null],
        ],
    ])

    {{-- Links to perform quick actions --}}
    @if ($invoice->status === App\Models\Invoice::STATUS_CANCELLED)
        <div class="row mb-0">
            <div class="col-12">
                <div class="card border border-danger">
                    <div class="card-header bg-transparent border-danger">
                        <h5 class="my-0 text-danger"><i class="mdi mdi-block-helper me-3"></i>This invoice is Cancelled</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            {!! $invoice->cancel_note !!}
                        </p>
                    </div>
                    {{-- /.card-body --}}
                    <div class="card-footer">
                        <a href="{{ route('admin.mark.as.unpaid.invoice', $invoice->id) }}"
                            class="btn btn-success btn-sm waves-effect waves-light">Mark as Unpaid</a>
                    </div>
                </div>
                {{-- /.card --}}
            </div>
            {{-- /.col --}}
        </div>
        {{-- /.row --}}
    @else
        <div class="row mb-3">
            <div class="col-md-6 col-sm-12">
                <a class="btn btn-success waves-effect waves-light" href="{{ route('admin.invoices.edit', $invoice->id) }}">
                    <i class="ri-edit-line align-middle me-2"></i> Edit Invoice
                </a>
                @if ($invoice->total != $invoice->total_paid && $invoice->total != 0)
                    <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal"
                        data-bs-target="#addPayment">Add Payment</button>
                    @include('admin.invoice.add-payment')
                @endif
                @if ($invoice->total_paid <= 0)
                    <button type="button" class="btn btn-secondary waves-effect waves-light" data-bs-toggle="modal"
                        data-bs-target="#cancelInvoice">Mark as Cancelled</button>
                    @include('admin.invoice.cancel-invoice')
                @endif
            </div>
            <div class="col-md-6 col-sm-12 text-end">
                <p class="m-0">
                    @if ($invoice->is_published)
                        Published on:
                    @else
                        Will be published on
                    @endif
                    <span>{{ $invoice->published_on->format('l, jS M Y') }}</span>
                </p>
                @if ($invoice->is_recurring)
                    <p>
                        Invoice Frequency <strong>{{ ucwords($invoice->recurring_frequency) }}</strong>
                        Next Date: <strong>{{ $invoice->recurringInvoice->next_invoice_date->format('l, jS M Y') }}</strong>
                    </p>
                @endif
            </div>
        </div>
    @endif

    {{-- Show Invoice Account Summary --}}
    <div class="row">
        <div class="col-md-3 col-sm-6">
            <div class="card border border-primary">
                <div class="card-body">
                    <div class="card-text text-center">Sub Total</div>
                    <h4 class="card-title text-center">
                        {{ $currency['symbol'] }}
                        {{ $invoice->sub_total }}
                        {{ $currency['name'] }}
                    </h4>
                </div>
            </div>
            {{-- /.card --}}
        </div>
        {{-- /.col --}}
        <div class="col-md-3 col-sm-6">
            <div class="card border border-primary">
                <div class="card-body">
                    <div class="card-text text-center">Total</div>
                    <h4 class="card-title text-center">
                        {{ $currency['symbol'] }}
                        {{ $invoice->total }}
                        {{ $currency['name'] }}
                    </h4>
                </div>
            </div>
        </div>
        {{-- /.col --}}
        <div class="col-md-3 col-sm-6">
            <div class="card border border-primary">
                <div class="card-body">
                    <div class="card-text text-center">Total Paid</div>
                    <h4 class="card-title text-center">
                        {{ $currency['symbol'] }}
                        {{ $invoice->total_paid }}
                        {{ $currency['name'] }}
                    </h4>
                </div>
            </div>
        </div>
        {{-- /.col --}}
        <div class="col-md-3 col-sm-6">
            <div class="card border border-primary">
                <div class="card-body">
                    <div class="card-text text-center">Status</div>
                    <h4 class="card-title text-center">
                        {{-- {{ $invoice->status }} --}}
                        <span class="badge {{ $invoice->getStatusColor() }}">
                            {{ $invoice->getStatus() }}
                        </span>
                    </h4>
                </div>
            </div>
        </div>
        {{-- /.col --}}
    </div>
    {{-- /.row --}}

    {{-- Show Invoice Summary --}}
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-text">Invoiced To</div>
                    <h4 class="card-title">
                        @if (!is_null($invoice->user_id))
                            <a href="{{ route('admin.users.show', $invoice->user->id) }}">
                                {{ $invoice->user->name }}
                            </a>
                        @elseif (!is_null($invoice->company_id))
                            <a href="{{ route('admin.companies.show', $invoice->company->id) }}">
                                {{ $invoice->company->name }}
                            </a>
                        @endif
                    </h4>
                </div>
            </div>
        </div>
        {{-- /.col --}}
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-text">Invoice Label</div>
                    <h4 class="card-title">
                        <span class="badge"
                            style="background-color: {{ $invoice->label->bg_color }}; color:{{ $invoice->label->text_color }}">
                            {{ $invoice->label->name }}
                        </span>
                    </h4>
                </div>
            </div>
        </div>
        {{-- /.col --}}
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-text">Invoice Date</div>
                    <h4 class="card-title">
                        @if (!is_null($invoice->invoice_date))
                            {{ $invoice->invoice_date->format('l, jS M Y') }}
                        @else
                            <span class="text-danger">Not Set</span>
                        @endif
                    </h4>
                </div>
            </div>
        </div>
        {{-- /.col --}}
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-text">Due Date</div>
                    <h4 class="card-title">
                        @if (!is_null($invoice->due_date))
                            {{ $invoice->due_date->format('l, jS M Y') }}
                        @else
                            <span class="text-danger">Not Set</span>
                        @endif
                    </h4>
                </div>
            </div>
        </div>
        {{-- /.col --}}
    </div>
    {{-- /.row --}}

    {{-- Show Private Note --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Private Note</div>
                <div class="card-body">
                    @if (!is_null($invoice->private_note))
                        {!! $invoice->private_note !!}
                    @else
                        <span class="text-danger">No Private Note</span>
                    @endif
                </div>
                {{-- /.card-body --}}
            </div>
            {{-- /.card --}}
        </div>
        {{-- /.col --}}
    </div>
    {{-- /.row --}}

    {{-- Header Note --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Invoice Header Note</div>
                <div class="card-body">
                    @if (!is_null($invoice->header_note))
                        {!! $invoice->header_note !!}
                    @else
                        <span class="text-danger">No Header Note</span>
                    @endif
                </div>
                {{-- /.card-body --}}
            </div>
            {{-- /.card --}}
        </div>
        {{-- /.col --}}
    </div>
    {{-- /.row --}}

    {{-- Show Invoice Items --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Invoice Details</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 50%"><strong>Item</strong></th>
                                    <th style="width: 20%" class="text-center" style="width: 10%"><strong>Price</strong>
                                    </th>
                                    <th style="width: 10%" class="text-center" style="width: 10%"><strong>Quantity</strong>
                                    </th>
                                    <th style="width: 20%" class="text-end" style="width: 10%"><strong>Totals</strong>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $item->item_description }}</td>
                                        <td class="text-center">
                                            {{ $currency['symbol'] }}
                                            {{ currencyFormat($item->unit_price) }}
                                            {{ $currency['name'] }}
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">
                                            {{ $currency['symbol'] }}
                                            {{ currencyFormat($item->unit_price * $item->quantity) }}
                                            {{ $currency['name'] }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="thick-line"></td>
                                    <td class="thick-line"></td>
                                    <td class="thick-line text-center">
                                        <strong>Subtotal</strong>
                                    </td>
                                    <td class="thick-line text-end">
                                        {{ $currency['symbol'] }}
                                        {{ $invoice->sub_total }}
                                        {{ $currency['name'] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="no-line"></td>
                                    <td class="no-line"></td>
                                    <td class="no-line text-center">
                                        <strong>Discount</strong>
                                    </td>
                                    <td class="no-line text-end">
                                        {{ $currency['symbol'] }}
                                        {{ $invoice->discount }}
                                        {{ $currency['name'] }}
                                    </td>
                                </tr>
                                @if (!is_null($invoice->tax))
                                    <tr>
                                        <td class="no-line"></td>
                                        <td class="no-line"></td>
                                        <td class="no-line text-center">
                                            <strong>Tax</strong>
                                        </td>
                                        <td class="no-line text-end">
                                            {{ $currency['symbol'] }}
                                            {{ $invoice->tax }}
                                            {{ $currency['name'] }}
                                        </td>
                                    </tr>
                                @endif
                                @if ($invoice->total_paid > 0)
                                    <tr>
                                        <td class="no-line"></td>
                                        <td class="no-line"></td>
                                        <td class="no-line text-center">
                                            <strong>Total</strong>
                                        </td>
                                        <td class="no-line text-end">
                                            {{ $currency['symbol'] }}
                                            {{ $invoice->total }}
                                            {{ $currency['name'] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="no-line"></td>
                                        <td class="no-line"></td>
                                        <td class="no-line text-center">
                                            <strong>Total Paid</strong>
                                        </td>
                                        <td class="no-line text-end">
                                            {{ $currency['symbol'] }}
                                            {{ $invoice->total_paid }}
                                            {{ $currency['name'] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="no-line"></td>
                                        <td class="no-line"></td>
                                        <td class="no-line text-center">
                                            <strong>Balance</strong>
                                        </td>
                                        <td class="no-line text-end">
                                            <h6 class="m-0">
                                                {{ $currency['symbol'] }}
                                                {{ $invoice->total - $invoice->total_paid }}
                                                {{ $currency['name'] }}
                                            </h6>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="no-line"></td>
                                        <td class="no-line"></td>
                                        <td class="no-line text-center">
                                            <strong>Total</strong>
                                        </td>
                                        <td class="no-line text-end">
                                            <h6 class="m-0">
                                                {{ $currency['symbol'] }}
                                                {{ currencyFormat($invoice->total) }}
                                                {{ $currency['name'] }}
                                            </h6>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    {{-- /.table-responsive --}}
                </div>
                {{-- /.card-body --}}
                <div class="card-footer">
                    <a class="btn btn-success btn-sm waves-effect waves-light"
                        href="{{ route('admin.invoices.edit', $invoice->id) }}">
                        <i class="ri-edit-line align-middle me-2"></i> Edit Invoice
                    </a>
                </div>
                {{-- /.card-footer --}}
            </div>
            {{-- /.card --}}
        </div>
        {{-- /.col --}}
    </div>
    {{-- /.row --}}

    {{-- Transaction Details --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Transaction Details
                </div>
                {{-- /.card-header --}}
                <div class="card-body">
                    <div class="transactionLoading" style="display: none;">
                        <div class="spinner-border text-secondary m-1" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div class="table-responsive transactions-table">
                        <table class="table table-hover table-bordered" id="transactions-table">
                            <thead>
                                <tr>
                                    <th>Amount</th>
                                    <th>Transaction ID</th>
                                    <th>Transaction Date</th>
                                    <th>Payment Method</th>
                                    <th>Created At</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="transactionItems">
                                @foreach ($payments as $payment)
                                    <tr data-id="{{ $payment->id }}">
                                        <td>{{ $payment->amount }}</td>
                                        <td>{{ $payment->transaction_number }}</td>
                                        <td>{{ $payment->transaction_date->format('l, F j, Y') }}</td>
                                        <td>{{ $payment->payment_method }}</td>
                                        <td>{{ $payment->created_at->format('l, F j, Y') }}</td>
                                        <td>
                                            <button type="button"
                                                class="btn btn-danger btn-sm waves-effect waves-light removePayment"
                                                data-payment-id="{{ $payment->id }}">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- /.card-body --}}
            </div>
            {{-- /.card --}}
        </div>
        {{-- /.col --}}
    </div>
    {{-- /.row --}}

    {{-- Footer Note --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Invoice Footer Note</div>
                <div class="card-body">
                    @if (!is_null($invoice->footer_note))
                        {!! $invoice->footer_note !!}
                    @else
                        <span class="text-danger">No Footer Note</span>
                    @endif
                </div>
                {{-- /.card-body --}}
            </div>
            {{-- /.card --}}
        </div>
        {{-- /.col --}}
    </div>
    {{-- /.row --}}

    {{-- Show Audit History --}}
    @hasrole('tester')
        @if (count($audits) > 0)
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            Audit History
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
        <div class="modal fade auditLog" tabindex="-1" aria-labelledby="auditLog" style="display: none;"
            aria-hidden="true">
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
@endpush

@push('scripts')
    @hasrole('tester')
        <script>
            $(document).ready(function() {
                // Audit Log Show Modal
                $('.show-audit-modal').click(function(e) {
                    e.preventDefault();
                    const invoiceId = $(this).data('audit-id');
                    // Fetch details via AJAX
                    $.ajax({
                        url: `{{ route('admin.products.audit', ':id') }}`.replace(':id',
                            invoiceId),
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
                    const invoiceId = $(this).data('audit-id');

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
                                url: `{{ route('admin.products.audit.delete', ':id') }}`
                                    .replace(
                                        ':id', invoiceId),
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
        $(document).ready(function() {
            // remove payment when button #removePayment click via ajax
            $('.removePayment').click(function(e) {
                e.preventDefault();
                const paymentId = $(this).data('payment-id');
                // Hide #transactions-table
                $('.transactions-table').hide();
                $('.transactionLoading').show();
                // Hide the row
                $(this).closest('tr').hide(); // This line hides the row containing the delete button
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: `{{ route('admin.invoice.remove-payment', ':id') }}`.replace(':id',
                        paymentId),
                    type: 'DELETE',
                    success: function(data) {
                        // Show success message
                        Swal.fire({
                            title: 'Success',
                            text: data.success,
                            icon: 'success',
                            showCancelButton: false
                        }).then((result) => {
                            location.reload();
                        });
                    },
                    error: function(error) {
                        // console.error('Error:', error);
                        Swal.fire({
                            title: 'Error',
                            text: data.error,
                            icon: 'error',
                            showCancelButton: false
                        });
                        // Show the row again if the delete operation fails
                        $(this).closest('tr')
                            .show(); // This line shows the row if the AJAX call fails
                    }
                });
            });
        });
    </script>
@endpush
