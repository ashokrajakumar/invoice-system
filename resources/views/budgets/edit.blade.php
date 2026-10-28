@extends('layouts.app')

@section('title', 'Edit Budget')

@section('content')
    <div class="container py-3">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Edit Budget</h2>
                <p class="text-muted mb-0">
                    Update the budget information below.
                </p>
            </div>

            <a href="{{ route('budgets.show', $budget) }}" class="btn btn-outline-secondary">
                Back to Details
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Please correct the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Budget Information</h5>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('budgets.update', $budget) }}" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="name" class="form-label">
                                Budget Name *
                            </label>

                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name', $budget->name) }}" maxlength="255" required>
                        </div>

                        <div class="col-md-6">
                            <label for="category" class="form-label">
                                Category
                            </label>

                            <input type="text" name="category" id="category" class="form-control"
                                value="{{ old('category', $budget->category) }}" maxlength="255">
                        </div>

                        <div class="col-md-6">
                            <label for="period_type" class="form-label">
                                Budget Period *
                            </label>

                            <select name="period_type" id="period_type" class="form-select" required>
                                <option value="monthly" {{ old('period_type', $budget->period_type) == 'monthly' ? 'selected' : '' }}>
                                    Monthly
                                </option>

                                <option value="yearly" {{ old('period_type', $budget->period_type) == 'yearly' ? 'selected' : '' }}>
                                    Yearly
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="amount" class="form-label">
                                Budget Amount (₹) *
                            </label>

                            <input type="number" name="amount" id="amount" class="form-control"
                                value="{{ old('amount', $budget->amount) }}" min="0.01" step="0.01" required>
                        </div>

                        <div class="col-md-6">
                            <label for="start_date" class="form-label">
                                Start Date *
                            </label>

                            <input type="date" name="start_date" id="start_date" class="form-control"
                                value="{{ old('start_date', $budget->start_date->format('Y-m-d')) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="end_date" class="form-label">
                                End Date *
                            </label>

                            <input type="date" name="end_date" id="end_date" class="form-control"
                                value="{{ old('end_date', $budget->end_date->format('Y-m-d')) }}" required>
                        </div>

                    </div>

                    <div class="alert alert-info mt-4 mb-0">
                        <strong>Note:</strong>
                        Changing the budget amount or dates does not change
                        the expenses already recorded.
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('budgets.show', $budget) }}" class="btn btn-light border">
                            Cancel
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Update Budget
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
@endsection