@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid py-3">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1">Dashboard</h2>
                <p class="text-muted mb-0">
                    Overview of your invoice and budget management system.
                </p>
            </div>

            <div class="text-muted">
                <i class="bi bi-calendar3 me-1"></i>
                {{ now()->format('d M Y') }}
            </div>
        </div>

        {{-- Main Summary Cards --}}
        <div class="row g-3 mb-4">

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Customers</span>
                            <span class="badge bg-primary-subtle text-primary p-2">
                                <i class="bi bi-people fs-5"></i>
                            </span>
                        </div>
                        <h3 class="fw-bold mb-1">{{ number_format($totalCustomers) }}</h3>
                        <a href="{{ route('customers.index') }}" class="small text-decoration-none">
                            View customers →
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Suppliers</span>
                            <span class="badge bg-success-subtle text-success p-2">
                                <i class="bi bi-truck fs-5"></i>
                            </span>
                        </div>
                        <h3 class="fw-bold mb-1">{{ number_format($totalSuppliers) }}</h3>
                        <a href="{{ route('suppliers.index') }}" class="small text-decoration-none">
                            View suppliers →
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Products</span>
                            <span class="badge bg-warning-subtle text-warning p-2">
                                <i class="bi bi-box-seam fs-5"></i>
                            </span>
                        </div>
                        <h3 class="fw-bold mb-1">{{ number_format($totalProducts) }}</h3>
                        <a href="{{ route('products.index') }}" class="small text-decoration-none">
                            View products →
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Payments</span>
                            <span class="badge bg-info-subtle text-info p-2">
                                <i class="bi bi-wallet2 fs-5"></i>
                            </span>
                        </div>
                        <h3 class="fw-bold mb-1">{{ number_format($totalPayments) }}</h3>
                        <a href="{{ route('payments.index') }}" class="small text-decoration-none">
                            View payments →
                        </a>
                    </div>
                </div>
            </div>

        </div>

        {{-- Financial Summary --}}
        <div class="row g-3 mb-4">

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <p class="text-muted mb-2">Total Sales</p>
                        <h4 class="fw-bold text-success mb-0">
                            ₹{{ number_format((float) $salesAmount, 2) }}
                        </h4>
                        <small class="text-muted">
                            {{ $totalSalesInvoices }} sales invoices
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <p class="text-muted mb-2">Total Purchases</p>
                        <h4 class="fw-bold text-primary mb-0">
                            ₹{{ number_format((float) $purchaseAmount, 2) }}
                        </h4>
                        <small class="text-muted">
                            {{ $totalPurchaseInvoices }} purchase invoices
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <p class="text-muted mb-2">Expenses</p>
                        <h4 class="fw-bold text-danger mb-0">
                            ₹{{ number_format((float) $totalExpenses, 2) }}
                        </h4>
                        <small class="text-muted">Total recorded expenses</small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <p class="text-muted mb-2">Budgets</p>
                        <h4 class="fw-bold mb-0">
                            {{ number_format($totalBudgets) }}
                        </h4>
                        <a href="{{ route('budgets.index') }}" class="small text-decoration-none">
                            Manage budgets →
                        </a>
                    </div>
                </div>
            </div>

        </div>

        {{-- Payment Overview --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Payment Overview</h5>
            </div>

            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <p class="text-muted mb-1">Amount Received from Customers</p>
                        <h4 class="fw-bold text-success">
                            ₹{{ number_format((float) $totalReceived, 2) }}
                        </h4>
                    </div>

                    <div class="col-md-4">
                        <p class="text-muted mb-1">Amount Paid to Suppliers</p>
                        <h4 class="fw-bold text-primary">
                            ₹{{ number_format((float) $totalPaid, 2) }}
                        </h4>
                    </div>

                    <div class="col-md-4">
                        <p class="text-muted mb-1">Outstanding Sales Invoice Amount</p>
                        <h4 class="fw-bold text-danger">
                            ₹{{ number_format(
        max(0, (float) $salesAmount - (float) $totalReceived),
        2
    ) }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Tables --}}
        <div class="row g-4">

            <div class="col-12 col-xl-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Recent Sales Invoices</h5>
                        <a href="{{ route('sales-invoices.index') }}" class="small text-decoration-none">
                            View All
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Invoice</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th class="text-end pe-3">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentSalesInvoices as $invoice)
                                                        <tr>
                                                            <td class="ps-3 fw-semibold">
                                                                <a href="{{ route('sales-invoices.show', $invoice) }}" class="text-decoration-none">
                                                                    {{ $invoice->invoice_number }}
                                                                </a>
                                                            </td>
                                                            <td>{{ $invoice->customer->name ?? '—' }}</td>
                                                            <td>
                                                                {{ $invoice->invoice_date
                                    ? $invoice->invoice_date->format('d M Y')
                                    : '—' }}
                                                            </td>
                                                            <td class="text-end pe-3 fw-semibold">
                                                                ₹{{ number_format((float) $invoice->total_amount, 2) }}
                                                            </td>
                                                        </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            No sales invoices found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Recent Expenses</h5>
                        <a href="{{ route('expenses.index') }}" class="small text-decoration-none">
                            View All
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Category</th>
                                    <th>Date</th>
                                    <th class="text-end pe-3">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentExpenses as $expense)
                                                        <tr>
                                                            <td class="ps-3">
                                                                <div class="fw-semibold">
                                                                    {{ $expense->category }}
                                                                </div>
                                                                <small class="text-muted">
                                                                    {{ $expense->description ?: 'No description' }}
                                                                </small>
                                                            </td>
                                                            <td>
                                                                {{ $expense->expense_date
                                    ? $expense->expense_date->format('d M Y')
                                    : '—' }}
                                                            </td>
                                                            <td class="text-end pe-3 fw-semibold text-danger">
                                                                ₹{{ number_format((float) $expense->amount, 2) }}
                                                            </td>
                                                        </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            No expenses found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection