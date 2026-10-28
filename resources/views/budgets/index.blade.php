@extends('layouts.app')

@section('title', 'Budgets')

@section('content')
    <div class="container-fluid py-3">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Budget Management</h2>
                <p class="text-muted mb-0">Manage your monthly and yearly budgets.</p>
            </div>

            <a href="{{ route('budgets.create') }}" class="btn btn-primary">
                + Create Budget
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Budget Name</th>
                                <th>Category</th>
                                <th>Period</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th class="text-end">Budget Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($budgets as $budget)
                                <tr>
                                    <td>{{ $budgets->firstItem() + $loop->index }}</td>

                                    <td class="fw-semibold">
                                        {{ $budget->name }}
                                    </td>

                                    <td>{{ $budget->category ?: '—' }}</td>

                                    <td>
                                        <span class="badge text-bg-info">
                                            {{ ucfirst($budget->period_type) }}
                                        </span>
                                    </td>

                                    <td>{{ $budget->start_date->format('d M Y') }}</td>

                                    <td>{{ $budget->end_date->format('d M Y') }}</td>

                                    <td class="text-end fw-semibold">
                                        ₹{{ number_format((float) $budget->amount, 2) }}
                                    </td>

                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('budgets.show', $budget) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                View
                                            </a>

                                            <a href="{{ route('budgets.edit', $budget) }}"
                                                class="btn btn-sm btn-outline-secondary">
                                                Edit
                                            </a>

                                            <form action="{{ route('budgets.destroy', $budget) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this budget?');">
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
                                    <td colspan="8" class="text-center py-5">
                                        <h5 class="text-muted">No budgets found</h5>
                                        <p class="text-muted mb-3">
                                            Create your first budget to get started.
                                        </p>
                                        <a href="{{ route('budgets.create') }}" class="btn btn-primary">
                                            Create Budget
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $budgets->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection