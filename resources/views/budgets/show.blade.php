@extends('layouts.app')

@section('title', 'Budget Details')

@section('content')
    <div class="container-fluid py-3">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1">Budget Details</h2>
                <p class="text-muted mb-0">
                    View budget information and recorded expenses.
                </p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('budgets.index') }}" class="btn btn-outline-secondary">
                    Back
                </a>

                <a href="{{ route('budgets.edit', $budget) }}" class="btn btn-primary">
                    Edit Budget
                </a>
            </div>
        </div>

        @php
            $spentAmount = (float) $budget->expenses->sum('amount');
            $budgetAmount = (float) $budget->amount;
            $remainingAmount = $budgetAmount - $spentAmount;

            $percentage = $budgetAmount > 0
                ? min(100, ($spentAmount / $budgetAmount) * 100)
                : 0;
        @endphp

        <div class="row g-3 mb-4">

            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-2">Total Budget</p>
                        <h3 class="fw-bold mb-0">
                            ₹{{ number_format($budgetAmount, 2) }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-2">Recorded Expenses</p>
                        <h3 class="fw-bold text-danger mb-0">
                            ₹{{ number_format($spentAmount, 2) }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-2">
                            {{ $remainingAmount >= 0 ? 'Remaining Budget' : 'Over Budget' }}
                        </p>
                        <h3 class="fw-bold {{ $remainingAmount >= 0 ? 'text-success' : 'text-danger' }} mb-0">
                            ₹{{ number_format(abs($remainingAmount), 2) }}
                        </h3>
                    </div>
                </div>
            </div>

        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Budget Information</h5>
            </div>

            <div class="card-body">
                <div class="row g-4">

                    <div class="col-md-6">
                        <small class="text-muted d-block">Budget Name</small>
                        <strong>{{ $budget->name }}</strong>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted d-block">Category</small>
                        <strong>{{ $budget->category ?: 'Not specified' }}</strong>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted d-block">Period</small>
                        <span class="badge text-bg-info">
                            {{ ucfirst($budget->period_type) }}
                        </span>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted d-block">Budget Amount</small>
                        <strong>₹{{ number_format($budgetAmount, 2) }}</strong>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted d-block">Start Date</small>
                        <strong>{{ $budget->start_date->format('d M Y') }}</strong>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted d-block">End Date</small>
                        <strong>{{ $budget->end_date->format('d M Y') }}</strong>
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-semibold">Budget Utilization</span>
                    <span>{{ number_format($percentage, 1) }}%</span>
                </div>

                <div class="progress" role="progressbar" aria-label="Budget utilization"
                    aria-valuenow="{{ (int) $percentage }}" aria-valuemin="0" aria-valuemax="100" style="height: 12px;">
                    <div class="progress-bar {{ $spentAmount > $budgetAmount ? 'bg-danger' : 'bg-success' }}"
                        style="width: {{ $percentage }}%">
                    </div>
                </div>

                @if ($spentAmount > $budgetAmount)
                    <p class="text-danger mt-2 mb-0">
                        Expenses have exceeded the budget.
                    </p>
                @endif
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Recorded Expenses</h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Expense Date</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($budget->expenses->sortByDesc('expense_date') as $expense)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $expense->expense_date->format('d M Y') }}</td>
                                    <td>{{ $expense->category }}</td>
                                    <td>{{ $expense->description ?: '—' }}</td>
                                    <td class="text-end fw-semibold">
                                        ₹{{ number_format((float) $expense->amount, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        No expenses have been recorded for this budget yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        @if ($budget->expenses->isNotEmpty())
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="4" class="text-end">Total Expenses</th>
                                    <th class="text-end">
                                        ₹{{ number_format($spentAmount, 2) }}
                                    </th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection