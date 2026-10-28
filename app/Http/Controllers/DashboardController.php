<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\SalesInvoice;
use App\Models\PurchaseInvoice;
use App\Models\Budget;
use App\Models\Expense;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = Customer::count();
        $totalSuppliers = Supplier::count();
        $totalProducts = Product::count();

        $totalSalesInvoices = SalesInvoice::count();
        $totalPurchaseInvoices = PurchaseInvoice::count();

        $totalBudgets = Budget::count();
        $totalExpenses = Expense::sum('amount');

        $totalPayments = Payment::count();

        $salesAmount = SalesInvoice::sum('total_amount');
        $purchaseAmount = PurchaseInvoice::sum('total_amount');

        $pendingSales = SalesInvoice::whereIn('status', [
            'pending',
            'partial',
        ])->sum('total_amount');

        $totalReceived = SalesInvoice::sum('paid_amount');
        $totalPaid = PurchaseInvoice::sum('paid_amount');

        $recentSalesInvoices = SalesInvoice::with('customer')
            ->latest()
            ->take(5)
            ->get();

        $recentExpenses = Expense::latest('expense_date')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalCustomers',
            'totalSuppliers',
            'totalProducts',
            'totalSalesInvoices',
            'totalPurchaseInvoices',
            'totalBudgets',
            'totalExpenses',
            'totalPayments',
            'salesAmount',
            'purchaseAmount',
            'pendingSales',
            'totalReceived',
            'totalPaid',
            'recentSalesInvoices',
            'recentExpenses'
        ));
    }
}
