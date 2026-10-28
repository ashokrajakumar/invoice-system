@extends('layouts.app')

@section('title', 'Products & Services')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Products | Invoice System</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body class="bg-light">

        <div class="container py-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold">Products & Services</h2>
                    <p class="text-muted mb-0">Manage products, prices and tax rates</p>
                </div>
                <a href="{{ route('products.create') }}" class="btn btn-primary">+ Add Product</a>
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
                                    <th>#</th>
                                    <th>Product / Service</th>
                                    <th>Price</th>
                                    <th>Tax (%)</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>{{ $products->firstItem() + $loop->index }}</td>
                                        <td>
                                            <strong>{{ $product->name }}</strong>
                                            <div class="small text-muted">
                                                {{ $product->description ?: 'No description' }}
                                            </div>
                                        </td>
                                        <td>₹{{ number_format((float) $product->price, 2) }}</td>
                                        <td>{{ number_format((float) $product->tax_rate, 2) }}%</td>
                                        <td>
                                            @if($product->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-nowrap">
                                            <a href="{{ route('products.show', $product) }}"
                                                class="btn btn-sm btn-outline-info">View</a>

                                            <a href="{{ route('products.edit', $product) }}"
                                                class="btn btn-sm btn-outline-primary">Edit</a>

                                            <form action="{{ route('products.destroy', $product) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Delete this product?')">
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
                                        <td colspan="6" class="text-center text-muted py-4">
                                            No products found. Add your first product.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </div>

            <!-- <a href="{{ route('customers.index') }}" class="btn btn-link mt-3">Customers</a>
                <a href="{{ route('suppliers.index') }}" class="btn btn-link mt-3">Suppliers</a> -->
        </div>

    </body>

    </html>
@endsection