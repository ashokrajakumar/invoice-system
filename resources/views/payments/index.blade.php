@extends('layouts.app')

@section('title', 'Payments')

@section('content')
    <div class="container-fluid py-3">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1">Payment Management</h2>
                <p class="text-muted mb-0">
                    Track payments received and payments made.
                </p>
            </div>

            <a href="{{ route('payments.create') }}" class="btn btn-primary">
                + Record Payment
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-2">Payments on This Page</p>
                        <h4 class="fw-bold mb-0">{{ $payments->count() }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-2">Amount on This Page</p>
                        <h4 class="fw-bold text-success mb-0">
                            ₹{{ number_format((float) $payments->sum('amount'), 2) }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-2">Total Payment Records</p>
                        <h4 class="fw-bold mb-0">{{ $payments->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Payment Date</th>
                                <th>Invoice</th>
                                <th>Type</th>
                                <th>Payment Method</th>
                                <th>Reference</th>
                                <th class="text-end">Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($payments as $payment)
                                @php
                                    $isSales = !is_null($payment->sales_invoice_id);
                                    $invoice = $isSales
                                        ? $payment->salesInvoice
                                        : $payment->purchaseInvoice;
                                @endphp

                                <tr>
                                    <td>
                                        {{ $payments->firstItem() + $loop->index }}
                                    </td>

                                    <td>
                                        {{ $payment->payment_date->format('d M Y') }}
                                    </td>

                                    <td>
                                        @if ($invoice)
                                            {{ $invoice->invoice_number }}
                                        @else
                                            <span class="text-muted">Invoice unavailable</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($isSales)
                                            <span class="badge text-bg-success">
                                                Received
                                            </span>
                                        @else
                                            <span class="badge text-bg-warning">
                                                Paid Out
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}
                                    </td>

                                    <td>
                                        {{ $payment->reference_number ?: '—' }}
                                    </td>

                                    <td class="text-end fw-semibold">
                                        ₹{{ number_format((float) $payment->amount, 2) }}
                                    </td>

                                    <td>
                                        <div class="d-flex flex-wrap gap-2">
                                            <a href="{{ route('payments.show', $payment) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                View
                                            </a>

                                            <form action="{{ route('payments.destroy', $payment) }}" method="POST"
                                                onsubmit="return confirm('Delete this payment and update the related invoice balance?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <h5 class="text-muted">No payments found</h5>
                                        <p class="text-muted mb-3">
                                            Record your first payment to get started.
                                        </p>

                                        <a href="{{ route('payments.create') }}" class="btn btn-primary">
                                            Record Payment
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $payments->links() }}
                </div>

            </div>
        </div>

    </div>
@endsection