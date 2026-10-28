@extends('layouts.app')

@section('title', 'Create Budget')

@section('content')
    <div class="container py-3">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Create Budget</h2>
                <p class="text-muted mb-0">
                    Set a spending limit for a selected period.
                </p>
            </div>

            <a href="{{ route('budgets.index') }}" class="btn btn-secondary">
                Back to Budgets
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Please correct these errors:</strong>
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
                <form action="{{ route('budgets.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="name" class="form-label">
                                Budget Name *
                            </label>

                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
                                placeholder="e.g. Monthly Office Expenses" maxlength="255" required>
                        </div>

                        <div class="col-md-6">
                            <label for="category" class="form-label">
                                Category
                            </label>

                            <input type="text" name="category" id="category" class="form-control"
                                value="{{ old('category') }}" placeholder="e.g. Office, Travel, Marketing" maxlength="255">
                        </div>

                        <div class="col-md-6">
                            <label for="period_type" class="form-label">
                                Budget Period *
                            </label>

                            <select name="period_type" id="period_type" class="form-select" required>
                                <option value="">Select Period</option>

                                <option value="monthly" {{ old('period_type') == 'monthly' ? 'selected' : '' }}>
                                    Monthly
                                </option>

                                <option value="yearly" {{ old('period_type') == 'yearly' ? 'selected' : '' }}>
                                    Yearly
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="amount" class="form-label">
                                Budget Amount (₹) *
                            </label>

                            <input type="number" name="amount" id="amount" class="form-control" value="{{ old('amount') }}"
                                placeholder="Enter budget amount" min="0.01" step="0.01" required>
                        </div>

                        <div class="col-md-6">
                            <label for="start_date" class="form-label">
                                Start Date *
                            </label>

                            <input type="date" name="start_date" id="start_date" class="form-control"
                                value="{{ old('start_date') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="end_date" class="form-label">
                                End Date *
                            </label>

                            <input type="date" name="end_date" id="end_date" class="form-control"
                                value="{{ old('end_date') }}" required>
                        </div>

                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('budgets.index') }}" class="btn btn-light border">
                            Cancel
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Save Budget
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const periodSelect = document.getElementById('period_type');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            function updateEndDate() {
                if (!startDateInput.value || !periodSelect.value) {
                    return;
                }

                const startDate = new Date(startDateInput.value + 'T12:00:00');
                const endDate = new Date(startDate);

                if (periodSelect.value === 'monthly') {
                    endDate.setMonth(endDate.getMonth() + 1);
                    endDate.setDate(0);
                } else if (periodSelect.value === 'yearly') {
                    endDate.setFullYear(endDate.getFullYear() + 1);
                    endDate.setDate(0);
                } else {
                    return;
                }

                const year = endDate.getFullYear();
                const month = String(endDate.getMonth() + 1).padStart(2, '0');
                const day = String(endDate.getDate()).padStart(2, '0');

                endDateInput.value = `${year}-${month}-${day}`;
            }

            periodSelect.addEventListener('change', updateEndDate);
            startDateInput.addEventListener('change', updateEndDate);

            endDateInput.addEventListener('change', function () {
                endDateInput.setCustomValidity(
                    endDateInput.value && startDateInput.value &&
                        endDateInput.value < startDateInput.value
                        ? 'End date must be on or after the start date.'
                        : ''
                );
            });
        });
    </script>
@endsection