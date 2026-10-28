@extends('layouts.app')

@section('title', 'Payment Details')

@section('content')
    <div class="container py-4">

        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <div>
                <h2 class="fw-bold mb-1">Payment Details</h2>
                <p class="text-muted mb-0">View the complete payment information.</p>
            </div>

            <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                Back to Payments
            </a>
        </div>

        @php
            $isSales = !is_null($payment->sales_invoice_id);
            $invoice = $isSales
                ? $payment->salesInvoice
                : $payment->purchaseInvoice;
        @endphp

        {{-- Payment Summary --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="row align-items-center g-3">
                    <div class="col-md-8">
                        <p class="text-muted mb-1">Payment Amount</p>
                        <h2 class="fw-bold text-success mb-2">
                            ₹{{ number_format((float) $payment->amount, 2) }}
                        </h2>

                        <span class="badge {{ $isSales ? 'bg-success' : 'bg-primary' }}">
                            {{ $isSales ? 'Customer Payment Received' : 'Supplier Payment Made' }}
                        </span>
                    </div>

                    <div class="col-md-4 text-md-end">
                        <p class="text-muted mb-1">Payment Date</p>
                        <h5 class="mb-0">
                            {{ $payment->payment_date
        ? $payment->payment_date->format('d M Y')
        : '—' }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Information --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-semibold mb-0">Payment Information</h5>
            </div>

            <div class="card-body p-4">
                <div class="row g-4">

                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">Payment ID</small>
                        <strong>#{{ $payment->id }}</strong>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">Payment Method</small>
                        <strong>
                            {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}
                        </strong>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">Reference Number</small>
                        <strong>{{ $payment->reference_number ?: 'Not provided' }}</strong>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">Recorded On</small>
                        <strong>{{ $payment->created_at->format('d M Y, h:i A') }}</strong>
                    </div>

                    <div class="col-12">
                        <small class="text-muted d-block mb-1">Notes</small>
                        <p class="mb-0">
                            {{ $payment->notes ?: 'No notes available.' }}
                        </p>
                    </div>

                </div>
            </div>
        </div>

        {{-- Related Invoice --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="fw-semibold mb-0">Related Invoice</h5>
            </div>

            <div class="card-body p-4">
                @if ($invoice)
                            <div class="row g-4">

                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">Invoice Type</small>
                                    <strong>
                                        {{ $isSales ? 'Sales Invoice' : 'Purchase Invoice' }}
                                    </strong>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">Invoice Number</small>
                                    <strong>{{ $invoice->invoice_number }}</strong>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">
                                        {{ $isSales ? 'Customer' : 'Supplier' }}
                                    </small>

                                    <strong>
                                        @if ($isSales)
                                            {{ $invoice->customer->name ?? 'Customer unavailable' }}
                                        @else
                                            {{ $invoice->supplier->name ?? 'Supplier unavailable' }}
                                        @endif
                                    </strong>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">Invoice Date</small>
                                    <strong>
                                        {{ $invoice->invoice_date
                        ? $invoice->invoice_date->format('d M Y')
                        : '—' }}
                                    </strong>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">Invoice Total</small>
                                    <strong>
                                        ₹{{ number_format((float) $invoice->total_amount, 2) }}
                                    </strong>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">Total Paid</small>
                                    <strong class="text-success">
                                        ₹{{ number_format((float) $invoice->paid_amount, 2) }}
                                    </strong>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">Outstanding Balance</small>
                                    <strong class="text-danger">
                                        ₹{{ number_format(
                        max(0, (float) $invoice->total_amount - (float) $invoice->paid_amount),
                        2
                    ) }}
                                    </strong>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">Invoice Status</small>

                                    @php
                                        $statusClass = match ($invoice->status) {
                                            'paid' => 'bg-success',
                                            'partial' => 'bg-info text-dark',
                                            'overdue' => 'bg-danger',
                                            default => 'bg-warning text-dark',
                                        };
                                    @endphp

                                    <span class="badge {{ $statusClass }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </div>

                            </div>
                @else
                    <div class="alert alert-warning mb-0">
                        The related invoice could not be found.
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection