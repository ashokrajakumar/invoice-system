<?php

namespace App\Http\Controllers;

use App\Models\SalesInvoice;
use App\Models\PurchaseInvoice;
use App\Models\Expense;
use App\Models\Payment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
        ]);

        $from = $validated['from'] ?? now()->startOfMonth()->toDateString();
        $to = $validated['to'] ?? now()->toDateString();

        $salesInvoices = SalesInvoice::with('customer')
            ->whereBetween('invoice_date', [$from, $to])
            ->latest('invoice_date')
            ->get();

        $purchaseInvoices = PurchaseInvoice::with('supplier')
            ->whereBetween('invoice_date', [$from, $to])
            ->latest('invoice_date')
            ->get();

        $expenses = Expense::whereBetween('expense_date', [$from, $to])
            ->latest('expense_date')
            ->get();

        $payments = Payment::with(['salesInvoice', 'purchaseInvoice'])
            ->whereBetween('payment_date', [$from, $to])
            ->latest('payment_date')
            ->get();

        $salesTotal = $salesInvoices->sum('total_amount');
        $purchaseTotal = $purchaseInvoices->sum('total_amount');
        $expenseTotal = $expenses->sum('amount');

        $receivedTotal = $payments
            ->whereNotNull('sales_invoice_id')
            ->sum('amount');

        $paidTotal = $payments
            ->whereNotNull('purchase_invoice_id')
            ->sum('amount');

        return view('reports.index', compact(
            'from',
            'to',
            'salesInvoices',
            'purchaseInvoices',
            'expenses',
            'payments',
            'salesTotal',
            'purchaseTotal',
            'expenseTotal',
            'receivedTotal',
            'paidTotal'
        ));
    }
}
