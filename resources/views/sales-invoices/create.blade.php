
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Create Sales Invoice</h2>
            <p class="text-muted mb-0">Create a new customer invoice.</p>
        </div>
        <a href="{{ route('sales-invoices.index') }}" class="btn btn-secondary">
            Back to Invoices
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('sales-invoices.store') }}" method="POST" id="invoiceForm">
        @csrf

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Invoice Details</h5>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="customer_id" class="form-label">Customer *</label>
                        <select name="customer_id" id="customer_id"
                                class="form-select" required>
                            <option value="">Select Customer</option>

                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="invoice_date" class="form-label">Invoice Date *</label>
                        <input type="date" name="invoice_date" id="invoice_date"
                               class="form-control"
                               value="{{ old('invoice_date', date('Y-m-d')) }}"
                               required>
                    </div>

                    <div class="col-md-3">
                        <label for="due_date" class="form-label">Due Date *</label>
                        <input type="date" name="due_date" id="due_date"
                               class="form-control"
                               value="{{ old('due_date', date('Y-m-d', strtotime('+7 days'))) }}"
                               required>
                    </div>

                    <div class="col-12">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control"
                                  rows="2"
                                  placeholder="Additional notes...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Products</h5>

                <button type="button" class="btn btn-sm btn-primary" id="addProduct">
                    + Add Product
                </button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="min-width: 220px;">Product</th>
                                <th style="min-width: 100px;">Price</th>
                                <th style="min-width: 100px;">Tax (%)</th>
                                <th style="min-width: 110px;">Quantity</th>
                                <th style="min-width: 130px;">Line Total</th>
                                <th style="width: 80px;">Action</th>
                            </tr>
                        </thead>

                        <tbody id="productRows">
                            <tr class="product-row">
                                <td>
                                    <select name="items[0][product_id]"
                                            class="form-select product-select" required>
                                        <option value="">Select Product</option>

                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}"
                                                data-price="{{ $product->price }}"
                                                data-tax="{{ $product->tax_rate }}">
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td class="price-cell">₹0.00</td>
                                <td class="tax-cell">0%</td>

                                <td>
                                    <input type="number"
                                           name="items[0][quantity]"
                                           class="form-control quantity-input"
                                           min="0.01" step="0.01"
                                           value="1" required>
                                </td>

                                <td class="line-total">₹0.00</td>

                                <td>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger remove-product"
                                            disabled>
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if ($products->isEmpty())
                    <div class="alert alert-warning mb-0">
                        No active products found. Please add a product before creating an invoice.
                    </div>
                @endif
            </div>
        </div>

        <div class="row justify-content-end mb-4">
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal</span>
                            <strong id="subtotalDisplay">₹0.00</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Tax</span>
                            <strong id="taxDisplay">₹0.00</strong>
                        </div>

                        <div class="mb-3">
                            <label for="discount_amount" class="form-label">
                                Discount (₹)
                            </label>
                            <input type="number"
                                   name="discount_amount"
                                   id="discount_amount"
                                   class="form-control"
                                   min="0" step="0.01"
                                   value="{{ old('discount_amount', 0) }}">
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Grand Total</span>
                            <strong class="fs-5 text-success" id="grandTotalDisplay">
                                ₹0.00
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('sales-invoices.index') }}"
               class="btn btn-light border">Cancel</a>

            <button type="submit" class="btn btn-success"
                    {{ $products->isEmpty() ? 'disabled' : '' }}>
                Create Invoice
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const productRows = document.getElementById('productRows');
    const addProductButton = document.getElementById('addProduct');
    const discountInput = document.getElementById('discount_amount');

    let rowIndex = 1;

    function formatCurrency(amount) {
        return '₹' + amount.toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function calculateTotals() {
        let subtotal = 0;
        let taxTotal = 0;

        document.querySelectorAll('.product-row').forEach(function (row) {
            const select = row.querySelector('.product-select');
            const quantityInput = row.querySelector('.quantity-input');

            const option = select.options[select.selectedIndex];
            const price = Number(option.dataset.price || 0);
            const taxRate = Number(option.dataset.tax || 0);
            const quantity = Number(quantityInput.value || 0);

            const lineSubtotal = price * quantity;
            const lineTax = lineSubtotal * taxRate / 100;
            const lineTotal = lineSubtotal + lineTax;

            row.querySelector('.price-cell').textContent =
                formatCurrency(price);

            row.querySelector('.tax-cell').textContent =
                taxRate + '%';

            row.querySelector('.line-total').textContent =
                formatCurrency(lineTotal);

            subtotal += lineSubtotal;
            taxTotal += lineTax;
        });

        const discount = Math.max(0, Number(discountInput.value || 0));
        const grandTotal = subtotal - Math.min(discount, subtotal) + taxTotal;

        document.getElementById('subtotalDisplay').textContent =
            formatCurrency(subtotal);

        document.getElementById('taxDisplay').textContent =
            formatCurrency(taxTotal);

        document.getElementById('grandTotalDisplay').textContent =
            formatCurrency(grandTotal);
    }

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.product-row');

        rows.forEach(function (row) {
            row.querySelector('.remove-product').disabled = rows.length === 1;
        });
    }

    function addProductRow() {
        const firstRow = document.querySelector('.product-row');
        const newRow = firstRow.cloneNode(true);

        newRow.querySelector('.product-select').name =
            'items[' + rowIndex + '][product_id]';

        newRow.querySelector('.product-select').selectedIndex = 0;

        newRow.querySelector('.quantity-input').name =
            'items[' + rowIndex + '][quantity]';

        newRow.querySelector('.quantity-input').value = '1';

        newRow.querySelector('.price-cell').textContent = '₹0.00';
        newRow.querySelector('.tax-cell').textContent = '0%';
        newRow.querySelector('.line-total').textContent = '₹0.00';

        newRow.querySelector('.remove-product').disabled = false;

        productRows.appendChild(newRow);

        rowIndex++;

        updateRemoveButtons();
        calculateTotals();
    }

    addProductButton.addEventListener('click', addProductRow);

    productRows.addEventListener('change', function (event) {
        if (event.target.matches('.product-select')) {
            calculateTotals();
        }
    });

    productRows.addEventListener('input', function (event) {
        if (event.target.matches('.quantity-input')) {
            calculateTotals();
        }
    });

    productRows.addEventListener('click', function (event) {
        const removeButton = event.target.closest('.remove-product');

        if (removeButton && productRows.querySelectorAll('.product-row').length > 1) {
            removeButton.closest('.product-row').remove();
            updateRemoveButtons();
            calculateTotals();
        }
    });

    discountInput.addEventListener('input', calculateTotals);

    document.getElementById('invoiceForm').addEventListener('submit', function (event) {
        const invoiceDate = document.getElementById('invoice_date').value;
        const dueDate = document.getElementById('due_date').value;

        if (dueDate < invoiceDate) {
            event.preventDefault();
            alert('Due date must be on or after the invoice date.');
        }
    });

    updateRemoveButtons();
    calculateTotals();
});
</script>
@endsection
