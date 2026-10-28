<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\SalesInvoice;
use App\Models\PurchaseInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with([
            'salesInvoice',
            'purchaseInvoice',
        ])->latest('payment_date')->paginate(10);

        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $salesInvoices = SalesInvoice::orderBy('invoice_date')
            ->get();

        $purchaseInvoices = PurchaseInvoice::orderBy('invoice_date')
            ->get();

        return view('payments.create', compact(
            'salesInvoices',
            'purchaseInvoices'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_type' => 'required|in:sales,purchase',
            'sales_invoice_id' => 'nullable|exists:sales_invoices,id',
            'purchase_invoice_id' => 'nullable|exists:purchase_invoices,id',
            'amount' => 'required|numeric|gt:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,card,upi,cheque,other',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if (
            ($validated['invoice_type'] === 'sales'
                && empty($validated['sales_invoice_id']))
            ||
            ($validated['invoice_type'] === 'purchase'
                && empty($validated['purchase_invoice_id']))
        ) {
            throw ValidationException::withMessages([
                'invoice_type' => 'Please select the correct invoice.',
            ]);
        }

        $payment = DB::transaction(function () use ($validated) {
            if ($validated['invoice_type'] === 'sales') {
                $invoice = SalesInvoice::whereKey(
                    $validated['sales_invoice_id']
                )->lockForUpdate()->firstOrFail();

                $alreadyPaid = (float) $invoice->paid_amount;
                $invoiceTotal = (float) $invoice->total_amount;
            } else {
                $invoice = PurchaseInvoice::whereKey(
                    $validated['purchase_invoice_id']
                )->lockForUpdate()->firstOrFail();

                $alreadyPaid = (float) $invoice->paid_amount;
                $invoiceTotal = (float) $invoice->total_amount;
            }

            $amount = (float) $validated['amount'];
            $balance = round($invoiceTotal - $alreadyPaid, 2);

            if ($amount > $balance) {
                throw ValidationException::withMessages([
                    'amount' => 'Payment cannot exceed the invoice balance of ₹'
                        . number_format(max(0, $balance), 2) . '.',
                ]);
            }

            $payment = Payment::create([
                'sales_invoice_id' => $validated['invoice_type'] === 'sales'
                    ? $validated['sales_invoice_id']
                    : null,

                'purchase_invoice_id' => $validated['invoice_type'] === 'purchase'
                    ? $validated['purchase_invoice_id']
                    : null,

                'amount' => $amount,
                'payment_date' => $validated['payment_date'],
                'payment_method' => $validated['payment_method'],
                'reference_number' => $validated['reference_number'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            $invoice->paid_amount = round($alreadyPaid + $amount, 2);

            $invoice->status = $invoice->paid_amount >= $invoiceTotal
                ? 'paid'
                : 'partial';

            $invoice->save();

            return $payment;
        });

        return redirect()
            ->route('payments.index')
            ->with('success', 'Payment recorded successfully.');
    }

    public function show(Payment $payment)
    {
        $payment->load(['salesInvoice', 'purchaseInvoice']);

        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $salesInvoices = SalesInvoice::orderBy('invoice_date')->get();
        $purchaseInvoices = PurchaseInvoice::orderBy('invoice_date')->get();

        return view('payments.edit', compact(
            'payment',
            'salesInvoices',
            'purchaseInvoices'
        ));
    }

    public function update(Request $request, Payment $payment)
    {
        return redirect()
            ->route('payments.index')
            ->with('error', 'Editing payments is disabled to protect invoice balances. Delete and re-record the payment using a controlled reversal workflow.');
    }

    public function destroy(Payment $payment)
    {
        DB::transaction(function () use ($payment) {
            $payment = Payment::whereKey($payment->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($payment->sales_invoice_id) {
                $invoice = SalesInvoice::whereKey($payment->sales_invoice_id)
                    ->lockForUpdate()
                    ->firstOrFail();
            } elseif ($payment->purchase_invoice_id) {
                $invoice = PurchaseInvoice::whereKey($payment->purchase_invoice_id)
                    ->lockForUpdate()
                    ->firstOrFail();
            } else {
                $payment->delete();
                return;
            }

            $invoice->paid_amount = max(
                0,
                round((float) $invoice->paid_amount - (float) $payment->amount, 2)
            );

            if ($invoice->paid_amount <= 0) {
                $invoice->status = 'pending';
            } elseif ($invoice->paid_amount >= (float) $invoice->total_amount) {
                $invoice->status = 'paid';
            } else {
                $invoice->status = 'partial';
            }

            $invoice->save();

            $payment->delete();
        });

        return redirect()
            ->route('payments.index')
            ->with('success', 'Payment deleted and invoice balance updated.');
    }
}
