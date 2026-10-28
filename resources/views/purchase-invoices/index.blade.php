@extends('layouts.app')

@section('title', 'Purchase Invoices')

@section('content')
    <div class="container-fluid py-3">

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h2 class="fw-bold mb-1">Purchase Invoices</h2>
                <p class="text-muted mb-0">
                    Manage invoices received from your suppliers.
                </p>
            </div>

            <a href="{{ route('purchase-invoices.create') }}" class="btn btn-primary">
                + Create Purchase Invoice
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

        <div class="card shadow-sm">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Invoice Number</th>
                                <th>Supplier</th>
                                <th>Invoice Date</th>
                                <th>Due Date</th>
                                <th>Total Amount</th>
                                <th>Paid Amount</th>
                                <th>Status</th>
                                <th style="min-width: 180px;">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($invoices as $invoice)
                                <tr>
                                    <td>
                                        {{ $invoices->firstItem() + $loop->index }}
                                    </td>

                                    <td class="fw-semibold">
                                        {{ $invoice->invoice_number }}
                                    </td>

                                    <td>
                                        {{ $invoice->supplier->name ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $invoice->invoice_date->format('d M Y') }}
                                    </td>

                                    <td>
                                        {{ $invoice->due_date->format('d M Y') }}
                                    </td>

                                    <td>
                                        ₹{{ number_format((float) $invoice->total_amount, 2) }}
                                    </td>

                                    <td>
                                        ₹{{ number_format((float) $invoice->paid_amount, 2) }}
                                    </td>

                                    <td>
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
                                    </td>

                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('purchase-invoices.show', $invoice) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                View
                                            </a>

                                            <a href="{{ route('purchase-invoices.edit', $invoice) }}"
                                                class="btn btn-sm btn-outline-secondary">
                                                Edit
                                            </a>

                                            <form action="{{ route('purchase-invoices.destroy', $invoice) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this purchase invoice?');">
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
                                    <td colspan="9" class="text-center py-5">
                                        <h5 class="text-muted">No purchase invoices found</h5>
                                        <p class="text-muted mb-3">
                                            Create your first purchase invoice to get started.
                                        </p>

                                        <a href="{{ route('purchase-invoices.create') }}" class="btn btn-primary">
                                            Create Purchase Invoice
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $invoices->links() }}
                </div>

            </div>
        </div>

    </div>
@endsection