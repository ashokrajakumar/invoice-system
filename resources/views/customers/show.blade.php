@extends('layouts.app')

@section('title', 'Customer Details')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Customer Details | Invoice System</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body class="bg-light">

        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="fw-bold mb-1">Customer Details</h2>
                            <p class="text-muted mb-0">View customer information.</p>
                        </div>

                        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Back to Customers</a>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">

                            <div class="mb-4">
                                <small class="text-muted">Customer Name</small>
                                <h5>{{ $customer->name }}</h5>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">Email</small>
                                    <p>{{ $customer->email ?: 'Not provided' }}</p>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">Phone</small>
                                    <p>{{ $customer->phone ?: 'Not provided' }}</p>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">GST Number</small>
                                    <p>{{ $customer->gst_number ?: 'Not provided' }}</p>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">Address</small>
                                    <p>{{ $customer->address ?: 'Not provided' }}</p>
                                </div>
                            </div>

                            <hr>

                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-primary">Edit Customer</a>

                            <a href="{{ route('customers.index') }}" class="btn btn-light border">Back</a>

                        </div>
                    </div>

                </div>
            </div>
        </div>

    </body>

    </html>
@endsection