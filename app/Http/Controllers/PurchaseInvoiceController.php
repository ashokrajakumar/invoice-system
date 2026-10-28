<?php

namespace App\Http\Controllers;

use App\Models\PurchaseInvoice;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseInvoiceController extends Controller
{
    public function index()
    {
        $invoices = PurchaseInvoice::with('supplier')
            ->latest()
            ->paginate(10);

        return view('purchase-invoices.index', compact('invoices'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();

        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('purchase-invoices.create', compact(
            'suppliers',
            'products'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|gt:0',
        ]);

        $invoice = DB::transaction(function () use ($validated) {
            $invoice = new PurchaseInvoice();

            $invoice->invoice_number = 'PINV-' . now()->format('YmdHis')
                . '-' . random_int(100, 999);

            $invoice->supplier_id = $validated['supplier_id'];
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
            $invoice->total_amount = round(
                $subtotal - $discount + $taxTotal,
                2
            );

            $invoice->save();

            return $invoice;
        });

        return redirect()
            ->route('purchase-invoices.show', $invoice)
            ->with('success', 'Purchase invoice created successfully.');
    }

    public function show(PurchaseInvoice $purchase_invoice)
    {
        $purchase_invoice->load([
            'supplier',
            'items.product',
            'payments'
        ]);

        return view('purchase-invoices.show', [
            'invoice' => $purchase_invoice,
        ]);
    }

    public function edit(PurchaseInvoice $purchase_invoice)
    {
        $suppliers = Supplier::orderBy('name')->get();

        return view('purchase-invoices.edit', [
            'invoice' => $purchase_invoice,
            'suppliers' => $suppliers,
        ]);
    }

    public function update(Request $request, PurchaseInvoice $purchase_invoice)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $subtotal = (float) $purchase_invoice->subtotal;
        $discount = (float) ($validated['discount_amount'] ?? 0);

        if ($discount > $subtotal) {
            throw ValidationException::withMessages([
                'discount_amount' => 'Discount cannot exceed the subtotal.',
            ]);
        }

        $purchase_invoice->update([
            'supplier_id' => $validated['supplier_id'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'discount_amount' => $discount,
            'total_amount' => round(
                $subtotal - $discount + (float) $purchase_invoice->tax_amount,
                2
            ),
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('purchase-invoices.show', $purchase_invoice)
            ->with('success', 'Purchase invoice updated successfully.');
    }

    public function destroy(PurchaseInvoice $purchase_invoice)
    {
        if ($purchase_invoice->payments()->exists()) {
            return redirect()
                ->route('purchase-invoices.index')
                ->with('error', 'Invoice has payments and cannot be deleted.');
        }

        $purchase_invoice->delete();

        return redirect()
            ->route('purchase-invoices.index')
            ->with('success', 'Purchase invoice deleted successfully.');
    }
}
