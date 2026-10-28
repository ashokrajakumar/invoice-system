
@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Edit Sales Invoice</h2>
            <p class="text-muted mb-0">
                Invoice: {{ $invoice->invoice_number }}
            </p>
        </div>

        <a href="{{ route('sales-invoices.show', $invoice) }}"
           class="btn btn-secondary">
            Back to Invoice
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix these errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Invoice Information</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('sales-invoices.update', $invoice) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="invoice_number" class="form-label">
                            Invoice Number
                        </label>

                        <input type="text"
                               id="invoice_number"
                               class="form-control"
                               value="{{ $invoice->invoice_number }}"
                               readonly>
                    </div>

                    <div class="col-md-6">
                        <label for="customer_id" class="form-label">
                            Customer *
                        </label>

                        <select name="customer_id"
                                id="customer_id"
                                class="form-select"
                                required>

                            <option value="">Select Customer</option>

                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ old('customer_id', $invoice->customer_id) == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="invoice_date" class="form-label">
                            Invoice Date *
                        </label>

                        <input type="date"
                               name="invoice_date"
                               id="invoice_date"
                               class="form-control"
                               value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label for="due_date" class="form-label">
                            Due Date *
                        </label>

                        <input type="date"
                               name="due_date"
                               id="due_date"
                               class="form-control"
                               value="{{ old('due_date', $invoice->due_date->format('Y-m-d')) }}"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label for="subtotal" class="form-label">
                            Subtotal
                        </label>

                        <input type="text"
                               id="subtotal"
                               class="form-control"
                               value="₹{{ number_format((float) $invoice->subtotal, 2) }}"
                               readonly>
                    </div>

                    <div class="col-md-6">
                        <label for="tax_amount" class="form-label">
                            Tax Amount
                        </label>

                        <input type="text"
                               id="tax_amount"
                               class="form-control"
                               value="₹{{ number_format((float) $invoice->tax_amount, 2) }}"
                               readonly>
                    </div>

                    <div class="col-md-6">
                        <label for="discount_amount" class="form-label">
                            Discount (₹)
                        </label>

                        <input type="number"
                               name="discount_amount"
                               id="discount_amount"
                               class="form-control"
                               min="0"
                               max="{{ $invoice->subtotal }}"
                               step="0.01"
                               value="{{ old('discount_amount', $invoice->discount_amount) }}"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Grand Total</label>

                        <input type="text"
                               id="grand_total"
                               class="form-control fw-bold text-success"
                               value="₹{{ number_format((float) $invoice->total_amount, 2) }}"
                               readonly>
                    </div>

                    <div class="col-12">
                        <label for="notes" class="form-label">Notes</label>

                        <textarea name="notes"
                                  id="notes"
                                  class="form-control"
                                  rows="3">{{ old('notes', $invoice->notes) }}</textarea>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('sales-invoices.show', $invoice) }}"
                       class="btn btn-light border">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Update Invoice
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const subtotal = Number(@json((float) $invoice->subtotal));
    const taxAmount = Number(@json((float) $invoice->tax_amount));
    const discountInput = document.getElementById('discount_amount');
    const grandTotalInput = document.getElementById('grand_total');

    function updateTotal() {
        const discount = Math.max(0, Number(discountInput.value || 0));
        const total = subtotal - discount + taxAmount;

        grandTotalInput.value = '₹' + total.toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    discountInput.addEventListener('input', updateTotal);

    document.querySelector('form').addEventListener('submit', function (event) {
        const invoiceDate = document.getElementById('invoice_date').value;
        const dueDate = document.getElementById('due_date').value;
        const discount = Number(discountInput.value || 0);

        if (dueDate < invoiceDate) {
            event.preventDefault();
            alert('Due date must be on or after the invoice date.');
            return;
        }

        if (discount > subtotal) {
            event.preventDefault();
            alert('Discount cannot exceed the subtotal.');
        }
    });

    updateTotal();
});
</script>
@endsection
