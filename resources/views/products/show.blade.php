@extends('layouts.app')

@section('title', 'Product Details')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Product Details | Invoice System</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body class="bg-light">

        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="fw-bold">Product Details</h2>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Back</a>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="mb-4">
                                <small class="text-muted">Product / Service</small>
                                <h4>{{ $product->name }}</h4>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">Description</small>
                                <p>{{ $product->description ?: 'No description provided' }}</p>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">Price</small>
                                    <h5>₹{{ number_format((float) $product->price, 2) }}</h5>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">Tax Rate</small>
                                    <h5>{{ number_format((float) $product->tax_rate, 2) }}%</h5>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">Status</small>
                                    <p>
                                        @if($product->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <hr>

                            <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">
                                Edit Product
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-light border">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>

    </html>

@endsection