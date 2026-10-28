@extends('layouts.app')

@section('title', 'Record Payment')

@section('content')
    <div class="container py-3">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Record Payment</h2>
                <p class="text-muted mb-0">
                    Record a payment received or a payment made.
                </p>
            </div>

            <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                Back to Payments
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
                <h5 class="mb-0">Payment Information</h5>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('payments.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="invoice_type" class="form-label">
                                Payment Type *
                            </label>

                            <select name="invoice_type" id="invoice_type" class="form-select" required>
                                <option value="">Select Payment Type</option>

                                <option value="sales" {{ old('invoice_type') == 'sales' ? 'selected' : '' }}>
                                    Customer Payment (Sales Invoice)
                                </option>

                                <option value="purchase" {{ old('invoice_type') == 'purchase' ? 'selected' : '' }}>
                                    Supplier Payment (Purchase Invoice)
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6" id="sales_invoice_group">
                            <label for="sales_invoice_id" class="form-label">
                                Sales Invoice
                            </label>

                            <select name="sales_invoice_id" id="sales_invoice_id" class="form-select">
                                <option value="">Select Sales Invoice</option>

                                @foreach ($salesInvoices as $invoice)
                                    @php
                                        $balance = (float) $invoice->total_amount
                                            - (float) $invoice->paid_amount;
                                    @endphp

                                    @if ($balance > 0)
                                        <option value="{{ $invoice->id }}" data-balance="{{ number_format($balance, 2, '.', '') }}"
                                            {{ old('sales_invoice_id') == $invoice->id ? 'selected' : '' }}>
                                            {{ $invoice->invoice_number }}
                                            — {{ $invoice->customer->name ?? 'Customer' }}
                                            — Balance ₹{{ number_format($balance, 2) }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6" id="purchase_invoice_group">
                            <label for="purchase_invoice_id" class="form-label">
                                Purchase Invoice
                            </label>

                            <select name="purchase_invoice_id" id="purchase_invoice_id" class="form-select">
                                <option value="">Select Purchase Invoice</option>

                                @foreach ($purchaseInvoices as $invoice)
                                    @php
                                        $balance = (float) $invoice->total_amount
                                            - (float) $invoice->paid_amount;
                                    @endphp

                                    @if ($balance > 0)
                                        <option value="{{ $invoice->id }}" data-balance="{{ number_format($balance, 2, '.', '') }}"
                                            {{ old('purchase_invoice_id') == $invoice->id ? 'selected' : '' }}>
                                            {{ $invoice->invoice_number }}
                                            — {{ $invoice->supplier->name ?? 'Supplier' }}
                                            — Balance ₹{{ number_format($balance, 2) }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="amount" class="form-label">
                                Payment Amount (₹) *
                            </label>

                            <input type="number" name="amount" id="amount" class="form-control" value="{{ old('amount') }}"
                                min="0.01" step="0.01" placeholder="Enter payment amount" required>

                            <small id="balance_hint" class="text-muted">
                                Select an invoice to see its outstanding balance.
                            </small>
                        </div>

                        <div class="col-md-6">
                            <label for="payment_date" class="form-label">
                                Payment Date *
                            </label>

                            <input type="date" name="payment_date" id="payment_date" class="form-control"
                                value="{{ old('payment_date', date('Y-m-d')) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="payment_method" class="form-label">
                                Payment Method *
                            </label>

                            <select name="payment_method" id="payment_method" class="form-select" required>
                                @foreach ([
                                        'cash' => 'Cash',
                                        'bank_transfer' => 'Bank Transfer',
                                        'card' => 'Card',
                                        'upi' => 'UPI',
                                        'cheque' => 'Cheque',
                                        'other' => 'Other'
                                    ] as $value => $label)
                                    <option value="{{ $value }}" {{ old('payment_method', 'cash') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="reference_number" class="form-label">
                                Reference Number
                            </label>

                            <input type="text" name="reference_number" id="reference_number" class="form-control"
                                value="{{ old('reference_number') }}" maxlength="255"
                                placeholder="Transaction ID / Cheque Number">
                        </div>

                        <div class="col-12">
                            <label for="notes" class="form-label">
                                Notes
                            </label>

                            <textarea name="notes" id="notes" class="form-control" rows="3" maxlength="1000"
                                placeholder="Optional payment notes">{{ old('notes') }}</textarea>
                        </div>

                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('payments.index') }}" class="btn btn-light border">
                            Cancel
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Save Payment
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.getElementById('invoice_type');

            const salesGroup = document.getElementById('sales_invoice_group');
            const purchaseGroup = document.getElementById('purchase_invoice_group');

            const salesSelect = document.getElementById('sales_invoice_id');
            const purchaseSelect = document.getElementById('purchase_invoice_id');

            const amountInput = document.getElementById('amount');
            const balanceHint = document.getElementById('balance_hint');

            function updateInvoiceFields() {
                const type = typeSelect.value;
                const isSales = type === 'sales';
                const isPurchase = type === 'purchase';

                salesGroup.classList.toggle('d-none', !isSales);
                purchaseGroup.classList.toggle('d-none', !isPurchase);

                salesSelect.disabled = !isSales;
                salesSelect.required = isSales;

                purchaseSelect.disabled = !isPurchase;
                purchaseSelect.required = isPurchase;

                if (!isSales) {
                    salesSelect.value = '';
                }

                if (!isPurchase) {
                    purchaseSelect.value = '';
                }

                updateBalance();
            }

            function updateBalance() {
                const selected = typeSelect.value === 'sales'
                    ? salesSelect.options[salesSelect.selectedIndex]
                    : typeSelect.value === 'purchase'
                        ? purchaseSelect.options[purchaseSelect.selectedIndex]
                        : null;

                const balance = selected && selected.dataset.balance
                    ? Number(selected.dataset.balance)
                    : null;

                if (balance === null) {
                    amountInput.removeAttribute('max');
                    balanceHint.textContent =
                        'Select an invoice to see its outstanding balance.';
                    return;
                }

                amountInput.max = balance.toFixed(2);

                balanceHint.textContent =
                    'Outstanding balance: ₹' + balance.toLocaleString('en-IN', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
            }

            typeSelect.addEventListener('change', updateInvoiceFields);
            salesSelect.addEventListener('change', updateBalance);
            purchaseSelect.addEventListener('change', updateBalance);

            document.querySelector('form').addEventListener('submit', function (event) {
                const selected = typeSelect.value === 'sales'
                    ? salesSelect.options[salesSelect.selectedIndex]
                    : purchaseSelect.options[purchaseSelect.selectedIndex];

                const balance = selected && selected.dataset.balance
                    ? Number(selected.dataset.balance)
                    : null;

                if (balance !== null && Number(amountInput.value) > balance) {
                    event.preventDefault();
                    alert('Payment cannot exceed the outstanding invoice balance.');
                }
            });

            updateInvoiceFields();
        });
    </script>
@endsection