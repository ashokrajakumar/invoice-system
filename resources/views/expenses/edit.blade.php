@extends('layouts.app')

@section('title', 'Edit Expense')

@section('content')
    <div class="container py-3">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Edit Expense</h2>
                <p class="text-muted mb-0">
                    Update the expense information.
                </p>
            </div>

            <a href="{{ route('expenses.show', $expense) }}" class="btn btn-outline-secondary">
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
                <h5 class="mb-0">Expense Information</h5>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('expenses.update', $expense) }}" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="category" class="form-label">
                                Expense Category *
                            </label>

                            <select name="category" id="category" class="form-select" required>
                                <option value="">Select Category</option>

                                @foreach (['Office', 'Travel', 'Rent', 'Utilities', 'Marketing', 'Salary', 'Equipment', 'Food', 'Other'] as $category)
                                    <option value="{{ $category }}" {{ old('category', $expense->category) == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach

                                @if (!in_array($expense->category, ['Office', 'Travel', 'Rent', 'Utilities', 'Marketing', 'Salary', 'Equipment', 'Food', 'Other']))
                                    <option value="{{ $expense->category }}" selected>
                                        {{ $expense->category }}
                                    </option>
                                @endif
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="amount" class="form-label">
                                Amount (₹) *
                            </label>

                            <input type="number" name="amount" id="amount" class="form-control"
                                value="{{ old('amount', $expense->amount) }}" min="0.01" step="0.01" required>
                        </div>

                        <div class="col-md-6">
                            <label for="expense_date" class="form-label">
                                Expense Date *
                            </label>

                            <input type="date" name="expense_date" id="expense_date" class="form-control"
                                value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="budget_id" class="form-label">
                                Related Budget
                            </label>

                            <select name="budget_id" id="budget_id" class="form-select">
                                <option value="">No Budget / Unassigned</option>

                                @foreach ($budgets as $budget)
                                    <option value="{{ $budget->id }}" {{ old('budget_id', $expense->budget_id) == $budget->id ? 'selected' : '' }}>
                                        {{ $budget->name }} — ₹{{ number_format((float) $budget->amount, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">
                                Description
                            </label>

                            <textarea name="description" id="description" class="form-control" rows="4" maxlength="1000"
                                placeholder="Enter expense details">{{ old('description', $expense->description) }}</textarea>
                        </div>

                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('expenses.show', $expense) }}" class="btn btn-light border">
                            Cancel
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Update Expense
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
@endsection