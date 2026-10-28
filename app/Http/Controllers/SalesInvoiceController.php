<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SalesInvoiceController extends Controller
{
    public function index()
    {
        $invoices = SalesInvoice::with('customer')
            ->latest()
            ->paginate(10);

        return view('sales-invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();

        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('sales-invoices.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|gt:0',
        ]);

        $invoice = DB::transaction(function () use ($validated) {
            $invoice = new SalesInvoice();

            $invoice->invoice_number = 'INV-' . now()->format('YmdHis')
                . '-' . random_int(100, 999);
            $invoice->customer_id = $validated['customer_id'];
            $invoice->invoice_date = $validated['invoice_date'];
            $invoice->due_date = $validated['due_date'];
            $invoice->discount_amount = $validated['discount_amount'] ?? 0;
            $invoice->notes = $validated['notes'] ?? null;
            $invoice->subtotal = 0;
            $invoice->tax_amount = 0;
            $invoice->total_amount = 0;
            $invoice->paid_amount = 0;
            $invoice->status = 'pending';
            $invoice->save();

            $subtotal = 0;
            $taxTotal = 0;

            foreach ($validated['items'] as $item) {
                $product = Product::where('is_active', true)
                    ->findOrFail($item['product_id']);

                $quantity = (float) $item['quantity'];
                $unitPrice = (float) $product->price;
                $taxRate = (float) $product->tax_rate;

                $lineSubtotal = round($quantity * $unitPrice, 2);
                $lineTax = round($lineSubtotal * $taxRate / 100, 2);
                $lineTotal = round($lineSubtotal + $lineTax, 2);

                $invoice->items()->create([
                    'product_id' => $product->id,
                    'description' => $product->name,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount_amount' => 0,
                    'tax_rate' => $taxRate,
                    'tax_amount' => $lineTax,
                    'total_amount' => $lineTotal,
                ]);

                $subtotal += $lineSubtotal;
                $taxTotal += $lineTax;
            }

            $discount = (float) ($validated['discount_amount'] ?? 0);

            if ($discount > $subtotal) {
                throw ValidationException::withMessages([
                    'discount_amount' => 'Discount cannot exceed the subtotal.',
                ]);
            }

            $invoice->subtotal = round($subtotal, 2);
            $invoice->tax_amount = round($taxTotal, 2);
            $invoice->total_amount = round($subtotal - $discount + $taxTotal, 2);
            $invoice->save();

            return $invoice;
        });

        return redirect()
            ->route('sales-invoices.show', $invoice)
            ->with('success', 'Sales invoice created successfully.');
    }

    public function show(SalesInvoice $sales_invoice)
    {
        $sales_invoice->load(['customer', 'items.product', 'payments']);

        return view('sales-invoices.show', [
            'invoice' => $sales_invoice,
        ]);
    }

    public function edit(SalesInvoice $sales_invoice)
    {
        $customers = Customer::orderBy('name')->get();

        return view('sales-invoices.edit', [
            'invoice' => $sales_invoice->load('items'),
            'customers' => $customers,
        ]);
    }

    public function update(Request $request, SalesInvoice $sales_invoice)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $subtotal = (float) $sales_invoice->subtotal;
        $discount = (float) ($validated['discount_amount'] ?? 0);

        if ($discount > $subtotal) {
            throw ValidationException::withMessages([
                'discount_amount' => 'Discount cannot exceed the subtotal.',
            ]);
        }

        $sales_invoice->update([
            'customer_id' => $validated['customer_id'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'discount_amount' => $discount,
            'total_amount' => round(
                $subtotal - $discount + (float) $sales_invoice->tax_amount,
                2
            ),
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('sales-invoices.show', $sales_invoice)
            ->with('success', 'Sales invoice updated successfully.');
    }

    public function destroy(SalesInvoice $sales_invoice)
    {
        if ($sales_invoice->payments()->exists()) {
            return redirect()
                ->route('sales-invoices.index')
                ->with('error', 'Invoice has payments and cannot be deleted.');
        }

        $sales_invoice->delete();

        return redirect()
            ->route('sales-invoices.index')
            ->with('success', 'Sales invoice deleted successfully.');
    }
}
