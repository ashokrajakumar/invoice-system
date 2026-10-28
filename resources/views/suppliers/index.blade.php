@extends('layouts.app')

@section('title', 'Suppliers')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Suppliers | Invoice System</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body class="bg-light">

        <div class="container py-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold">Suppliers</h2>
                    <p class="text-muted mb-0">Manage your suppliers</p>
                </div>
                <a href="{{ route('suppliers.create') }}" class="btn btn-primary">+ Add Supplier</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>GST Number</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($suppliers as $supplier)
                                    <tr>
                                        <td>{{ $suppliers->firstItem() + $loop->index }}</td>
                                        <td>{{ $supplier->name }}</td>
                                        <td>{{ $supplier->email ?: '-' }}</td>
                                        <td>{{ $supplier->phone ?: '-' }}</td>
                                        <td>{{ $supplier->gst_number ?: '-' }}</td>
                                        <td class="text-nowrap">
                                            <a href="{{ route('suppliers.show', $supplier) }}"
                                                class="btn btn-sm btn-outline-info">View</a>

                                            <a href="{{ route('suppliers.edit', $supplier) }}"
                                                class="btn btn-sm btn-outline-primary">Edit</a>

                                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Delete this supplier?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            No suppliers found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $suppliers->links('pagination::bootstrap-5') }}
                </div>
            </div>

            <!-- <a href="{{ route('customers.index') }}" class="btn btn-link mt-3">Go to Customers</a> -->
        </div>

    </body>

    </html>
@endsection