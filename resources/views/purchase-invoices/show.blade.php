@extends('layouts.app')

@section('title', 'Purchase Invoice Details')

@section('content')
    <div class="container-fluid py-3">

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h2 class="fw-bold mb-1">Purchase Invoice Details</h2>
                <p class="text-muted mb-0">
                    Invoice: {{ $invoice->invoice_number }}
                </p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('purchase-invoices.index') }}" class="btn btn-secondary">
                    Back
                </a>

                <a href="{{ route('purchase-invoices.edit', $invoice) }}" class="btn btn-primary">
                    Edit Invoice
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Invoice Information</h5>
            </div>

            <div class="card-body">
                <div class="row g-4">

                    <div class="col-md-4">
                        <small class="text-muted">Invoice Number</small>
                        <h6 class="mt-1">{{ $invoice->invoice_number }}</h6>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted">Supplier Name</small>
                        <h6 class="mt-1">
                            {{ $invoice->supplier->name ?? 'N/A' }}
                        </h6>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted">Status</small>
                        <div class="mt-1">
                            @php
                                $statusClass = match ($invoice->status) {
                                    'paid' => 'bg-success',
                                    'overdue' => 'bg-danger',
                                    'partial' => 'bg-info text-dark',
                                    default => 'bg-warning text-dark',
                                };
                            @endphp

                            <span class="badge {{ $statusClass }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted">Invoice Date</small>
                        <h6 class="mt-1">
                            {{ $invoice->invoice_date->format('d M Y') }}
                        </h6>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted">Due Date</small>
                        <h6 class="mt-1">
                            {{ $invoice->due_date->format('d M Y') }}
                        </h6>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted">Supplier Email</small>
                        <h6 class="mt-1">
                            {{ $invoice->supplier->email ?? 'N/A' }}
                        </h6>
                    </div>

                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Purchased Products</h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Tax Rate</th>
                                <th>Tax Amount</th>
                                <th>Line Total</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($invoice->items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        {{ $item->description }}
                                    </td>

                                    <td>
                                        {{ number_format((float) $item->quantity, 2) }}
                                    </td>

                                    <td>
                                        ₹{{ number_format((float) $item->unit_price, 2) }}
                                    </td>

                                    <td>
                                        {{ number_format((float) $item->tax_rate, 2) }}%
                                    </td>

                                    <td>
                                        ₹{{ number_format((float) $item->tax_amount, 2) }}
                                    </td>

                                    <td class="fw-semibold">
                                        ₹{{ number_format((float) $item->total_amount, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        No products found for this invoice.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row justify-content-end">
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Invoice Summary</h5>
                    </div>

                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal</span>
                            <strong>
                                ₹{{ number_format((float) $invoice->subtotal, 2) }}
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Discount</span>
                            <strong>
                                ₹{{ number_format((float) $invoice->discount_amount, 2) }}
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Tax Amount</span>
                            <strong>
                                ₹{{ number_format((float) $invoice->tax_amount, 2) }}
                            </strong>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold">Grand Total</span>
                            <strong class="text-success fs-5">
                                ₹{{ number_format((float) $invoice->total_amount, 2) }}
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Paid Amount</span>
                            <strong>
                                ₹{{ number_format((float) $invoice->paid_amount, 2) }}
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span>Balance Due</span>
                            <strong class="text-danger">
                                ₹{{ number_format(
        max(0, (float) $invoice->total_amount - (float) $invoice->paid_amount),
        2
    ) }}
                            </strong>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        @if ($invoice->notes)
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Notes</h5>
                </div>

                <div class="card-body">
                    {{ $invoice->notes }}
                </div>
            </div>
        @endif

    </div>
@endsection