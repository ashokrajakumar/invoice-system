@extends('layouts.app')

@section('title', 'Reports')

@section('content')
    <div class="container-fluid py-3">

        <div class="mb-4">
            <h2 class="fw-bold mb-1">Financial Reports</h2>
            <p class="text-muted mb-0">
                Review sales, purchases, expenses and payment activity.
            </p>
        </div>

        {{-- Date Filter --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('reports.index') }}" method="GET">
                    <div class="row g-3 align-items-end">

                        <div class="col-md-4">
                            <label for="from" class="form-label">From Date</label>
                            <input type="date" name="from" id="from" class="form-control" value="{{ $from }}" required>
                        </div>

                        <div class="col-md-4">
                            <label for="to" class="form-label">To Date</label>
                            <input type="date" name="to" id="to" class="form-control" value="{{ $to }}" min="{{ $from }}"
                                required>
                        </div>

                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                Generate Report
                            </button>

                            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                                Reset
                            </a>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <p class="text-muted mb-3">
            Report period: <strong>{{ $from }}</strong> to
            <strong>{{ $to }}</strong>
        </p>

        {{-- Summary Cards --}}
        <div class="row g-3 mb-4">

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <p class="text-muted mb-2">Sales Invoiced</p>
                        <h4 class="fw-bold text-success mb-1">
                            ₹{{ number_format((float) $salesTotal, 2) }}
                        </h4>
                        <small class="text-muted">
                            {{ $salesInvoices->count() }} invoices
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <p class="text-muted mb-2">Purchases Invoiced</p>
                        <h4 class="fw-bold text-primary mb-1">
                            ₹{{ number_format((float) $purchaseTotal, 2) }}
                        </h4>
                        <small class="text-muted">
                            {{ $purchaseInvoices->count() }} invoices
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <p class="text-muted mb-2">Expenses Recorded</p>
                        <h4 class="fw-bold text-danger mb-1">
                            ₹{{ number_format((float) $expenseTotal, 2) }}
                        </h4>
                        <small class="text-muted">
                            {{ $expenses->count() }} expenses
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <p class="text-muted mb-2">Customer Payments Received</p>
                        <h4 class="fw-bold text-success mb-1">
                            ₹{{ number_format((float) $receivedTotal, 2) }}
                        </h4>
                        <small class="text-muted">Payments in selected period</small>
                    </div>
                </div>
            </div>

        </div>

        {{-- Payment Summary --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="row g-3">

                    <div class="col-md-6">
                        <p class="text-muted mb-1">Payments Made to Suppliers</p>
                        <h4 class="fw-bold text-primary">
                            ₹{{ number_format((float) $paidTotal, 2) }}
                        </h4>
                    </div>

                    <div class="col-md-6">
                        <p class="text-muted mb-1">Sales Minus Purchases and Expenses</p>
                        <h4 class="fw-bold">
                            ₹{{ number_format(
        (float) $salesTotal
        - (float) $purchaseTotal
        - (float) $expenseTotal,
        2
    ) }}
                        </h4>
                        <small class="text-muted">
                            A simple comparison, not a formal accounting profit calculation.
                        </small>
                    </div>

                </div>
            </div>
        </div>

        {{-- Sales Invoice Table --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Sales Invoices</h5>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Invoice Number</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-end pe-3">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($salesInvoices as $invoice)
                            <tr>
                                <td class="ps-3">
                                    <a href="{{ route('sales-invoices.show', $invoice) }}"
                                        class="text-decoration-none fw-semibold">
                                        {{ $invoice->invoice_number }}
                                    </a>
                                </td>
                                <td>{{ $invoice->customer->name ?? '—' }}</td>
                                <td>{{ $invoice->invoice_date?->format('d M Y') ?? '—' }}</td>
                                <td>{{ ucfirst($invoice->status) }}</td>
                                <td class="text-end pe-3">
                                    ₹{{ number_format((float) $invoice->total_amount, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No sales invoices found for this period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Purchase Invoice Table --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Purchase Invoices</h5>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Invoice Number</th>
                            <th>Supplier</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-end pe-3">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($purchaseInvoices as $invoice)
                            <tr>
                                <td class="ps-3">
                                    <a href="{{ route('purchase-invoices.show', $invoice) }}"
                                        class="text-decoration-none fw-semibold">
                                        {{ $invoice->invoice_number }}
                                    </a>
                                </td>
                                <td>{{ $invoice->supplier->name ?? '—' }}</td>
                                <td>{{ $invoice->invoice_date?->format('d M Y') ?? '—' }}</td>
                                <td>{{ ucfirst($invoice->status) }}</td>
                                <td class="text-end pe-3">
                                    ₹{{ number_format((float) $invoice->total_amount, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No purchase invoices found for this period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Expenses Table --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Expenses</h5>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Category</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th class="text-end pe-3">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expenses as $expense)
                            <tr>
                                <td class="ps-3">{{ $expense->category }}</td>
                                <td>{{ $expense->description ?: '—' }}</td>
                                <td>{{ $expense->expense_date?->format('d M Y') ?? '—' }}</td>
                                <td class="text-end pe-3">
                                    ₹{{ number_format((float) $expense->amount, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No expenses found for this period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Payments Table --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Payments</h5>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Date</th>
                            <th>Invoice</th>
                            <th>Type</th>
                            <th>Method</th>
                            <th class="text-end pe-3">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payments as $payment)
                            @php
                                $isSales = !is_null($payment->sales_invoice_id);
                                $relatedInvoice = $isSales
                                    ? $payment->salesInvoice
                                    : $payment->purchaseInvoice;
                            @endphp
                            <tr>
                                <td class="ps-3">
                                    {{ $payment->payment_date?->format('d M Y') ?? '—' }}
                                </td>
                                <td>{{ $relatedInvoice->invoice_number ?? '—' }}</td>
                                <td>
                                    {{ $isSales ? 'Received' : 'Paid Out' }}
                                </td>
                                <td>
                                    {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}
                                </td>
                                <td class="text-end pe-3 fw-semibold">
                                    ₹{{ number_format((float) $payment->amount, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No payments found for this period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection