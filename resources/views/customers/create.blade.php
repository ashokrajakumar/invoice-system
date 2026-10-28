@extends('layouts.app')

@section('title', 'Add Customer')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Customer | Invoice System</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body class="bg-light">

        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="fw-bold mb-1">Add Customer</h2>
                            <p class="text-muted mb-0">Enter customer information below.</p>
                        </div>

                        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                            Back
                        </a>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('customers.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Customer Name *</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control" rows="3">{{ old('address') }}</textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">GST Number</label>
                                    <input type="text" name="gst_number" class="form-control"
                                        value="{{ old('gst_number') }}">
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        Save Customer
                                    </button>

                                    <a href="{{ route('customers.index') }}" class="btn btn-light border">
                                        Cancel
                                    </a>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>

    </body>

    </html>
@endsection