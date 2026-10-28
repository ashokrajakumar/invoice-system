<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | Invoice Manager</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 250px;
            --primary: #183e52;
            --page-bg: #f5f7fb;
        }

        body {
            margin: 0;
            background: var(--page-bg);
            color: #263445;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: #183e52;
            color: #fff;
            padding: 24px 16px;
            overflow-y: auto;
            z-index: 1040;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 10px 26px;
            border-bottom: 1px solid rgba(255, 255, 255, .15);
            margin-bottom: 24px;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            background: #f0a45b;
            color: #183e52;
            font-size: 22px;
        }

        .brand-name {
            font-size: 17px;
            font-weight: 700;
            margin: 0;
        }

        .brand-subtitle {
            color: #bdcdd6;
            font-size: 11px;
            margin-top: 3px;
        }

        .menu-heading {
            color: #a9bdc8;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            padding: 0 12px;
            margin: 22px 0 10px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 11px 13px;
            margin-bottom: 5px;
            border-radius: 9px;
            color: #d5e0e6;
            text-decoration: none;
            font-size: 14px;
            transition: .2s;
        }

        .sidebar-link i {
            font-size: 17px;
            width: 20px;
            text-align: center;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: #f0a45b;
            color: #183e52;
        }

        .main-area {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .top-header {
            height: 76px;
            background: #fff;
            border-bottom: 1px solid #e8edf2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
        }

        .page-heading {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
        }

        .header-caption {
            font-size: 12px;
            color: #82909d;
            margin-top: 4px;
        }

        .user-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 600;
        }

        .user-icon {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            background: #e8f0f5;
            color: var(--primary);
            border-radius: 50%;
            font-size: 19px;
        }

        .content-area {
            padding: 28px;
        }

        .content-area .card {
            border: 1px solid #e9edf2;
            border-radius: 12px;
        }

        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform .25s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-area {
                margin-left: 0;
            }

            .sidebar-overlay.show {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(15, 30, 40, .45);
                z-index: 1030;
            }

            .top-header {
                padding: 0 16px;
            }

            .content-area {
                padding: 18px 14px;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <div class="brand-icon">
                <i class="bi bi-receipt-cutoff"></i>
            </div>
            <div>
                <p class="brand-name">Invoice Manager</p>
                <div class="brand-subtitle">Finance & Budget System</div>
            </div>
        </div>

        <div class="menu-heading">MAIN MENU</div>

        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>

        <a href="{{ route('customers.index') }}"
            class="sidebar-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Customers
        </a>

        <a href="{{ route('suppliers.index') }}"
            class="sidebar-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
            <i class="bi bi-truck"></i> Suppliers
        </a>

        <a href="{{ route('products.index') }}"
            class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Products
        </a>

        <div class="menu-heading">FINANCE</div>

        <a href="{{ route('sales-invoices.index') }}"
            class="sidebar-link {{ request()->routeIs('sales-invoices.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text"></i> Sales Invoices
        </a>

        <a href="{{ route('purchase-invoices.index') }}"
            class="sidebar-link {{ request()->routeIs('purchase-invoices.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-arrow-down"></i> Purchase Invoices
        </a>

        <a href="{{ route('budgets.index') }}"
            class="sidebar-link {{ request()->routeIs('budgets.*') ? 'active' : '' }}">
            <i class="bi bi-wallet2"></i> Budgets
        </a>

        <a href="{{ route('expenses.index') }}"
            class="sidebar-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
            <i class="bi bi-credit-card"></i> Expenses
        </a>

        <a href="{{ route('payments.index') }}"
            class="sidebar-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i> Payments
        </a>

        <div class="menu-heading">ANALYTICS</div>

        <a href="{{ route('reports.index') }}"
            class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> Reports
        </a>
    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main content -->
    <div class="main-area">

        <header class="top-header">
            <div class="d-flex align-items-center gap-3">
                <button type="button" class="btn btn-light d-lg-none" id="sidebarToggle" aria-label="Toggle sidebar">
                    <i class="bi bi-list fs-4"></i>
                </button>

                <div>
                    <h1 class="page-heading">@yield('title', 'Dashboard')</h1>
                    <div class="header-caption">
                        Manage your business finances in one place
                    </div>
                </div>
            </div>

            <div class="user-badge d-none d-sm-flex">
                <div class="user-icon">
                    <i class="bi bi-person"></i>
                </div>
                <div>
                    <div>Administrator</div>
                    <small class="text-secondary">Finance Management</small>
                </div>
            </div>
        </header>

        <main class="content-area">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

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

            @yield('content')
        </main>

        <footer class="text-center text-secondary small py-3">
            Invoice & Budget Management System
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function closeSidebar() {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        }

        sidebarToggle?.addEventListener('click', function () {
            sidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('show');
        });

        sidebarOverlay?.addEventListener('click', closeSidebar);

        sidebar.querySelectorAll('.sidebar-link').forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.innerWidth <= 991) {
                    closeSidebar();
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>