@extends('layouts.app')

@section('title', 'Expense Details')

@section('content')
    <div class="container py-3">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1">Expense Details</h2>
                <p class="text-muted mb-0">
                    View the complete information for this expense.
                </p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">
                    Back
                </a>

                <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-primary">
                    Edit Expense
                </a>
            </div>
        </div>

        <div class="row g-3 mb-4">

            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-2">Expense Amount</p>
                        <h3 class="fw-bold text-danger mb-0">
                            ₹{{ number_format((float) $expense->amount, 2) }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-2">Expense Category</p>
                        <h3 class="fw-bold mb-0">
                            {{ $expense->category }}
                        </h3>
                    </div>
                </div>
            </div>

        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Expense Information</h5>
            </div>

            <div class="card-body">
                <div class="row g-4">

                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">
                            Expense Date
                        </small>
                        <strong>
                            {{ $expense->expense_date->format('d M Y') }}
                        </strong>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">
                            Category
                        </small>
                        <span class="badge text-bg-secondary">
                            {{ $expense->category }}
                        </span>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">
                            Related Budget
                        </small>

                        @if ($expense->budget)
                            <a href="{{ route('budgets.show', $expense->budget) }}" class="text-decoration-none fw-semibold">
                                {{ $expense->budget->name }}
                            </a>
                        @else
                            <span class="text-muted">No budget assigned</span>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">
                            Budget Amount
                        </small>

                        @if ($expense->budget)
                            <strong>
                                ₹{{ number_format((float) $expense->budget->amount, 2) }}
                            </strong>
                        @else
                            <span class="text-muted">Not applicable</span>
                        @endif
                    </div>

                    <div class="col-12">
                        <small class="text-muted d-block mb-2">
                            Description
                        </small>

                        <div class="bg-light rounded p-3">
                            {{ $expense->description ?: 'No description provided.' }}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <form action="{{ route('expenses.destroy', $expense) }}" method="POST"
                onsubmit="return confirm('Are you sure you want to delete this expense?');">

                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-outline-danger">
                    Delete Expense
                </button>
            </form>
        </div>

    </div>
@endsection