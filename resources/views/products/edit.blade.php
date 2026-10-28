@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Edit Product | Invoice System</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body class="bg-light">

        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="fw-bold">Edit Product</h2>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Back</a>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    @foreach($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            @endif

                            <form action="{{ route('products.update', $product) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label class="form-label">Name *</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $product->name) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control"
                                        rows="3">{{ old('description', $product->description) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Price (₹) *</label>
                                    <input type="number" name="price" class="form-control" min="0" step="0.01"
                                        value="{{ old('price', $product->price) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tax Rate (%) *</label>
                                    <input type="number" name="tax_rate" class="form-control" min="0" max="100" step="0.01"
                                        value="{{ old('tax_rate', $product->tax_rate) }}" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Status *</label>
                                    <select name="is_active" class="form-select" required>
                                        <option value="1" @selected(old('is_active', (string) (int) $product->is_active) == '1')>Active</option>
                                        <option value="0" @selected(old('is_active', (string) (int) $product->is_active) == '0')>Inactive</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">Update Product</button>
                                <a href="{{ route('products.index') }}" class="btn btn-light border">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>

    </html>

@endsection