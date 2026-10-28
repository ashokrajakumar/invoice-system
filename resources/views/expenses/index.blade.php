@extends('layouts.app')

@section('title', 'Expenses')

@section('content')
    <div class="container-fluid py-3">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1">Expense Management</h2>
                <p class="text-muted mb-0">
                    Track and manage your recorded expenses.
                </p>
            </div>

            <a href="{{ route('expenses.create') }}" class="btn btn-primary">
                + Add Expense
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-2">Expenses on This Page</p>
                        <h4 class="fw-bold mb-0">{{ $expenses->count() }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-2">Amount on This Page</p>
                        <h4 class="fw-bold text-danger mb-0">
                            ₹{{ number_format((float) $expenses->sum('amount'), 2) }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-2">Total Records</p>
                        <h4 class="fw-bold mb-0">{{ $expenses->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Expense Date</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Budget</th>
                                <th class="text-end">Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($expenses as $expense)
                                <tr>
                                    <td>
                                        {{ $expenses->firstItem() + $loop->index }}
                                    </td>

                                    <td>
                                        {{ $expense->expense_date->format('d M Y') }}
                                    </td>

                                    <td>
                                        <span class="badge text-bg-secondary">
                                            {{ $expense->category }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $expense->description ?: '—' }}
                                    </td>

                                    <td>
                                        {{ $expense->budget?->name ?? 'Unassigned' }}
                                    </td>

                                    <td class="text-end fw-semibold text-danger">
                                        ₹{{ number_format((float) $expense->amount, 2) }}
                                    </td>

                                    <td>
                                        <div class="d-flex flex-wrap gap-2">
                                            <a href="{{ route('expenses.show', $expense) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                View
                                            </a>

                                            <a href="{{ route('expenses.edit', $expense) }}"
                                                class="btn btn-sm btn-outline-secondary">
                                                Edit
                                            </a>

                                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this expense?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <h5 class="text-muted">No expenses found</h5>
                                        <p class="text-muted mb-3">
                                            Start by recording your first expense.
                                        </p>

                                        <a href="{{ route('expenses.create') }}" class="btn btn-primary">
                                            Add Expense
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $expenses->links() }}
                </div>

            </div>
        </div>

    </div>
@endsection