@extends('layouts.app')

@section('title', 'Sales Invoices')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sales Invoices | Invoice System</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body class="bg-light">

        <div class="container py-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold">Sales Invoices</h2>
                    <p class="text-muted mb-0">Manage customer invoices</p>
                </div>
                <a href="{{ route('sales-invoices.create') }}" class="btn btn-primary">
                    + Create Invoice
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Invoice No.</th>
                                    <th>Customer</th>
                                    <th>Invoice Date</th>
                                    <th>Due Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $invoice)
                                                        <tr>
                                                            <td>{{ $invoice->invoice_number }}</td>
                                                            <td>{{ $invoice->customer->name }}</td>
                                                            <td>{{ $invoice->invoice_date->format('d M Y') }}</td>
                                                            <td>{{ $invoice->due_date->format('d M Y') }}</td>
                                                            <td>₹{{ number_format((float) $invoice->total_amount, 2) }}</td>
                                                            <td>
                                                                <span class="badge
                                                                                                                    {{ $invoice->status === 'paid' ? 'bg-success' :
                                    ($invoice->status === 'overdue' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                                                    {{ ucfirst($invoice->status) }}
                                                                </span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <a href="{{ route('sales-invoices.show', $invoice) }}"
                                                                    class="btn btn-sm btn-outline-info">View</a>
                                                                <a href="{{ route('sales-invoices.edit', $invoice) }}"
                                                                    class="btn btn-sm btn-outline-primary">Edit</a>
                                                                <form action="{{ route('sales-invoices.destroy', $invoice) }}" method="POST"
                                                                    class="d-inline" onsubmit="return confirm('Delete this invoice?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="btn btn-sm btn-outline-danger" type="submit">
                                                                        Delete
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            No sales invoices found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $invoices->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

    </body>

    </html>
@endsection