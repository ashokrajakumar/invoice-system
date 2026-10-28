@extends('layouts.app')

@section('content')
    <div class="container py-4">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Sales Invoice Details</h2>
                <p class="text-muted mb-0">
                    Invoice: {{ $invoice->invoice_number }}
                </p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('sales-invoices.index') }}" class="btn btn-secondary">
                    Back
                </a>

                <a href="{{ route('sales-invoices.edit', $invoice) }}" class="btn btn-primary">
                    Edit Invoice
                </a>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Invoice Information</h5>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <strong>Invoice Number</strong>
                        <p>{{ $invoice->invoice_number }}</p>
                    </div>

                    <div class="col-md-4">
                        <strong>Customer</strong>
                        <p>{{ $invoice->customer->name ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-4">
                        <strong>Status</strong>
                        <p>
                            <span class="badge bg-warning text-dark">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </p>
                    </div>

                    <div class="col-md-4">
                        <strong>Invoice Date</strong>
                        <p>{{ $invoice->invoice_date->format('d M Y') }}</p>
                    </div>

                    <div class="col-md-4">
                        <strong>Due Date</strong>
                        <p>{{ $invoice->due_date->format('d M Y') }}</p>
                    </div>

                    <div class="col-md-4">
                        <strong>Customer Email</strong>
                        <p>{{ $invoice->customer->email ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Invoice Items</h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Tax (%)</th>
                                <th>Tax Amount</th>
                                <th>Total</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($invoice->items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ number_format((float) $item->quantity, 2) }}</td>
                                    <td>₹{{ number_format((float) $item->unit_price, 2) }}</td>
                                    <td>{{ number_format((float) $item->tax_rate, 2) }}%</td>
                                    <td>₹{{ number_format((float) $item->tax_amount, 2) }}</td>
                                    <td>₹{{ number_format((float) $item->total_amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        No invoice items found.
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
                            <span>Tax</span>
                            <strong>
                                ₹{{ number_format((float) $invoice->tax_amount, 2) }}
                            </strong>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold">Grand Total</span>
                            <strong class="text-success">
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