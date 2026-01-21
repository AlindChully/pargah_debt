<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['ar','krd']) ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/employeepage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="/css/three-d.css">
    <link rel="stylesheet" href="{{ asset('css/lang.css') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
</head>

<body>

    <!-- Modal إضافة مدين -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fw-bold mb-0">{{ __('general.add a new debtor') }}</h5>
                    <button type="button" class="custom-close-btn" data-bs-dismiss="modal" aria-label="إغلاق">✖</button>
                </div>

                <form action="{{ route('customers.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label>{{ __('general.name') }}</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label>{{ __('general.phone') }}</label>
                                <input type="text" name="phone" class="form-control">
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary w-100">💾 {{ __('general.add customer') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal تعديل مدين -->
    <div class="modal fade" id="editCustomerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fw-bold mb-0">
                        ✏️ {{ __('general.update customer') }}: <span id="editCustomerName"></span>
                    </h5>
                    <button type="button" class="custom-close-btn" data-bs-dismiss="modal">✖</button>
                </div>

                <form id="editCustomerForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label>{{ __('general.name') }}</label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label>{{ __('general.phone') }}</label>
                                <input type="text" name="phone" id="edit_phone" class="form-control">
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success w-100">💾 {{ __('general.update customer') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add user Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fw-bold mb-0">
                        <i class="bi bi-person-plus"></i> {{ __('general.add user') }} <span id="addUserModal"></span>
                    </h5>
                    <button type="button" class="custom-close-btn" data-bs-dismiss="modal">✖</button>
                </div>

                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>{{ __('general.name') }}</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label>{{ __('general.phone') }}</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label>{{ __('general.password') }}</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary w-100">💾 {{ __('general.add user') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fw-bold mb-0">
                        <i class="bi bi-person"></i> {{ __('general.profile') }}
                    </h5>
                    <button type="button" class="custom-close-btn" data-bs-dismiss="modal">✖</button>
                </div>

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label>{{ __('general.name') }}</label>
                                <input type="text" name="name" value="{{ Auth::user()->name }}" id="user_name"
                                    class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label>{{ __('general.phone') }}</label>
                                <input type="text" name="phone" value="{{ Auth::user()->phone }}" id="user_phone"
                                    class="form-control">
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary w-100">💾 {{ __('general.update') }} </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal التقرير الشهري / السنوي -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fw-bold mb-0">
                        <i class="bi bi-bar-chart-fill"></i> {{ __('general.customer report') }}
                    </h5>
                    <button type="button" class="custom-close-btn" data-bs-dismiss="modal">✖</button>
                </div>

                <!-- Form -->
                <form method="GET" action="{{ route('reports.customers') }}" target="_blank">

                    <!-- Body -->
                    <div class="modal-body">
                        <div class="row g-3">

                            <!-- نوع التقرير -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('general.report type') }}</label>
                                <select name="type" class="form-select" onchange="this.form.submit()">
                                    <option value="monthly" {{ request('type','monthly')=='monthly'?'selected':'' }}>
                                        📅 {{ __('general.monthly report') }}
                                    </option>
                                    <option value="yearly" {{ request('type')=='yearly'?'selected':'' }}>
                                        📆 {{ __('general.annual report') }}
                                    </option>
                                </select>
                            </div>

                            <!-- الشهر -->
                            @if(request('type','monthly')=='monthly')
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('general.month') }}</label>
                                <input type="month" name="month" value="{{ request('month', now()->format('Y-m')) }}"
                                    class="form-control">
                            </div>
                            @else
                            <!-- السنة -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('general.year') }}</label>
                                <input type="number" name="year" value="{{ request('year', now()->year) }}" min="2020"
                                    class="form-control">
                            </div>
                            @endif

                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> {{ __('general.present the report') }}
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <div class="body">
        <h3>{{ __('general.dashboard') }}</h3>

        <!-- زر القائمة للهواتف فقط -->
        <button class="btn btn3D mb-3 menu" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileActions"
            aria-controls="mobileActions">
            ☰ {{ __('general.menu') }}
        </button>
        <a href="{{ route('dashboard.pdf') }}" target="_blank" class="btn btn3D mb-3 d-md-none pdf">
            <i class="bi bi-file-earmark-pdf"></i> PDF
        </a>

        <!-- Offcanvas -->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileActions" aria-labelledby="mobileActionsLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="mobileActionsLabel">
                    <i class="bi bi-person-circle"></i> {{ ucfirst(Auth::user()->name) }}
                </h5>
                <button type="button" class="btn-close  btn-close-white text-reset xbutton"
                    data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body d-flex flex-column gap-2">
                <button href="" class="btn offcanvas-btn mb-2" data-bs-toggle="modal" data-bs-target="#profileModal">
                    <i class="bi bi-person"></i> {{ __('general.profile') }}
                </button>
                @if(Auth::user()->type === 'super admin')
                <button href="" class="btn offcanvas-btn mb-2" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="bi bi-person-plus"></i> {{ __('general.add user') }}
                </button>
                <a href="{{ route('users.index') }}" target="_blank" class="btn offcanvas-btn mb-2">
                    <i class="bi bi-person"></i> {{ __('general.users') }}
                </a>
                @endif
                <button class="btn offcanvas-btn mb-2" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                    <i class="bi bi-person-plus"></i> {{ __('general.add new customer') }}
                </button>
                <button class="btn offcanvas-btn mb-2" data-bs-toggle="modal" data-bs-target="#reportModal">
                    <i class="bi bi-bar-chart"></i> {{ __('general.monthly / annual report') }}
                </button>
                <a href="{{ route('dashboard.print') }}" target="_blank" class="btn offcanvas-btn mb-2">
                    <i class="bi bi-printer"></i> {{ __('general.print') }}
                </a>
                <button onclick="exportExcel()" class="btn offcanvas-btn mb-2">
                    <i class="bi bi-file-earmark-excel"></i> {{ __('general.excel') }}
                </button>
                <button class="btn btn-outline-secondary dropdown-toggle offcanvas-btn mb-2" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-globe"></i> {{ app()->getLocale() === 'ar' ? 'العربية' : 'English' }}
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item {{ app()->getLocale() === 'krd' ? 'active' : '' }}"
                            href="{{ route('lang.switch', 'krd') }}">
                            کوردی
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ app()->getLocale() === 'ar' ? 'active' : '' }}"
                            href="{{ route('lang.switch', 'ar') }}">
                            {{ __('general.arabic') }}
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}"
                            href="{{ route('lang.switch', 'en') }}">
                            {{ __('general.english') }}
                        </a>
                    </li>
                </ul>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn offcanvas-btn mb-2 w-100">
                        <i class="bi bi-box-arrow-right"></i> {{ __('general.logout') }}
                    </button>
                </form>
            </div>
        </div>

        <button class="btn btn-primary btn3D mb-3 d-none d-md-inline-block" data-bs-toggle="modal"
            data-bs-target="#profileModal">
            <i class="bi bi-person"></i> {{ __('general.profile') }}
        </button>
        @if(Auth::user()->type === 'super admin')
        <button class="btn btn-primary btn3D mb-3 d-none d-md-inline-block" data-bs-toggle="modal"
            data-bs-target="#addUserModal">
            <i class="bi bi-person-plus"></i> {{ __('general.add user') }}
        </button>
        <a href="{{ route('users.index') }}" target="_blank"
            class="btn btn-primary btn3D mb-3 d-none d-md-inline-block">
            <i class="bi bi-person"></i> {{ __('general.users') }}
        </a>
        @endif
        <button class="btn btn-primary btn3D mb-3 d-none d-md-inline-block" data-bs-toggle="modal"
            data-bs-target="#addCustomerModal">
            <i class="bi bi-person-plus"></i> {{ __('general.add new customer') }}
        </button>
        <button class="btn btn-info btn3D mb-3 d-none d-md-inline-block" data-bs-toggle="modal"
            data-bs-target="#reportModal">
            <i class="bi bi-bar-chart"></i> {{ __('general.monthly / annual report') }}
        </button>
        <a href="{{ route('dashboard.print') }}" target="_blank"
            class="btn btn-dark btn3D mb-3 d-none d-md-inline-block">
            <i class="bi bi-printer"></i> {{ __('general.print') }}
        </a>
        <a href="{{ route('dashboard.pdf') }}" target="_blank"
            class="btn btn-success btn3D mb-3 d-none d-md-inline-block">
            <i class="bi bi-file-earmark-pdf"></i> PDF
        </a>
        <button onclick="exportExcel()" class="btn btn-success btn3D mb-3 d-none d-md-inline-block">
            <i class="bi bi-file-earmark-excel"></i> {{ __('general.excel') }}
        </button>
        <button class="btn dropdown-toggle btn-primary btn3D mb-3 d-none d-md-inline-block" type="button"
            data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-globe"></i> {{ app()->getLocale() === 'ar' ? 'العربية' : 'English' }}
        </button>
        <ul class="dropdown-menu">
            <li>
                <a class="dropdown-item {{ app()->getLocale() === 'krd' ? 'active' : '' }}"
                    href="{{ route('lang.switch', 'krd') }}">
                    {{ __('general.kurdi') }}
                </a>
            </li>
            <li>
                <a class="dropdown-item {{ app()->getLocale() === 'ar' ? 'active' : '' }}"
                    href="{{ route('lang.switch', 'ar') }}">
                    {{ __('general.arabic') }}
                </a>
            </li>
            <li>
                <a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}"
                    href="{{ route('lang.switch', 'en') }}">
                    {{ __('general.english') }}
                </a>
            </li>
        </ul>
        <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger btn3D mb-3 d-none d-md-inline-block">
                <i class="bi bi-box-arrow-right"></i> {{ __('general.logout') }}
            </button>
        </form>

        <div class="row mb-3 text-center">
            <div class="col-md-3">
                <div class="card p-2 total-card">
                    <small>{{ __('general.total debts unpaid') }}</small><strong>{{ $Debts->where('is_paid', false)->count() }}
                    </strong>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-2 bg-success-subtle total-card">
                    <small>{{ __('general.total amount paid') }}</small>
                    <strong>{{ number_format($paidTotal) }}</strong>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-2 bg-danger-subtle total-card">
                    <small>{{ __('general.total amount unpaid') }}</small>
                    <strong>{{ number_format($customers->sum(function($c) {
                            return $c->debts->where('is_paid', false)->sum('amount'); })) }}</strong>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" id="customerSearch" class="form-control input3D"
                    placeholder="🔍 {{ __('general.search') }}...">
            </div>
        </div>

        <div id="noResult" class="text-center mt-4" style="display:none;">
            <h5 class="text-danger">❌ {{ __('general.this is not a debt.') }}</h5>
        </div>
        
        <div class="table-wrapper" id="customersTable">
            <table class="table table-hover text-center align-middle teble3D desktop-table">
                <thead style="background-color: #EEEEEE;">
                    <tr>
                        <th>#</th>
                        <th>{{ __('general.name') }}</th>
                        <th>{{ __('general.phone') }}</th>
                        <th>{{ __('general.unpaid debt') }}</th>
                        <th>{{ __('general.unpaid amount') }}</th>
                        <th>{{ __('general.paid amount') }}</th>
                        <th>{{ __('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                    @php
                    $paid = $customer->receipts->sum('amount');
                    $unpaid = $customer->debts->where('is_paid', false)->sum('amount');
                    @endphp
                    <tr>
                        <th class="none"> {{ $loop->iteration }}</th>
                        <td>{{ ucfirst($customer->name) }}</td>
                        <td class="ltr" dir="ltr">{{ $customer->formatted_phone }}</td>
                        <td>{{ $customer->debts->where('is_paid', false)->count() }}</td>
                        <td style="color:red; font-weight:bold;">{{ number_format($unpaid) }}
                        </td>
                        <td style="color:green; font-weight:bold;">{{ number_format($paid) }}</td>
                        <td class="action">
                            <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-sm cardBtn3D primary">
                                <i class="bi bi-box-seam"></i> {{ __('general.details') }}
                            </a>

                            <button type="button" class="btn btn-sm editCustomerBtn cardBtn3D warning"
                                data-id="{{ $customer->id }}" data-name="{{ $customer->name }}"
                                data-phone="{{ $customer->phone }}" data-bs-toggle="modal"
                                data-bs-target="#editCustomerModal"> <i class="bi bi-pencil">
                                    {{ __('general.edit') }}</i>
                            </button>

                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button
                                    onclick="return confirm('هل أنت متأكد من حذف هذا المدين؟ سيتم حذف جميع ديونه أيضًا!');"
                                    class="btn btn-sm cardBtn3D danger"> <i class="bi bi-trash">
                                        {{ __('general.delete') }}</i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- بورسپونسڤێ دا جەتوەل بیتە کارت -->
        <div class="mobile-cards">
            @foreach($customers as $customer)
            @php
            $paid = $customer->receipts->sum('amount');
            $unpaid = $customer->debts->where('is_paid', false)->sum('amount');
            @endphp

            <div class="customer-card">
                <div class="card-header">
                    <strong>{{ ucfirst($customer->name) }}</strong>
                </div>

                <div class="card-body">
                    <div> 
                        <span>{{ __('general.phone') }}</span><b class="phone ltr" >{{ $customer->formatted_phone }}</b>
                    </div>
                    <div>
                        <span>{{ __('general.unpaid debt') }}</span><b>{{ $customer->debts->where('is_paid', false)->count() }}</b>
                    </div>
                    <div class="red"><span>{{ __('general.unpaid amount') }}</span><b>{{ number_format($unpaid) }}</b>
                    </div>
                    <div class="green"><span>{{ __('general.paid amount') }}</span><b>{{ number_format($paid) }}</b>
                    </div>
                </div>

                <div class="card-actions">
                    <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-sm cardBtn3D primary">
                        <i class="bi bi-box-seam"></i> {{ __('general.details') }}
                    </a>

                    <button type="button" class="btn btn-sm editCustomerBtn cardBtn3D warning"
                        data-id="{{ $customer->id }}" data-name="{{ $customer->name }}"
                        data-phone="{{ $customer->phone }}" data-bs-toggle="modal" data-bs-target="#editCustomerModal">
                        <i class="bi bi-pencil"> {{ __('general.edit') }}</i>
                    </button>

                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('هل أنت متأكد من حذف هذا المدين؟ سيتم حذف جميع ديونه أيضًا!');"
                            class="btn btn-sm cardBtn3D danger"> <i class="bi bi-trash"> {{ __('general.delete') }}</i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>

    <script>
        //search
        document.getElementById('customerSearch').addEventListener('keyup', function() {
            const value = this.value.toLowerCase();
            let found = false;

            /* ====== TABLE ====== */
            document.querySelectorAll('#customersTable tr').forEach(row => {
                const name = row.children[1]?.innerText.toLowerCase() || '';
                const phone = row.children[2]?.innerText.toLowerCase() || '';

                const match = name.includes(value) || phone.includes(value);
                row.style.display = match ? '' : 'none';
                if (match) found = true;
            });

            /* ====== MOBILE CARDS ====== */
            document.querySelectorAll('.customer-card').forEach(card => {
                const name = card.querySelector('.card-header strong')?.innerText.toLowerCase() || '';
                const phone = card.querySelector('.phone')?.innerText.toLowerCase() || '';

                const match = name.includes(value) || phone.includes(value);
                card.style.display = match ? '' : 'none';
                if (match) found = true;
            });

            /* ====== NO RESULT MESSAGE ====== */
            document.getElementById('noResult').style.display = found ? 'none' : 'block';
        });
        
        //Edit
        document.querySelectorAll('.editCustomerBtn').forEach(btn => {
            btn.addEventListener('click', function() {

                const id = this.dataset.id;
                const name = this.dataset.name;
                const phone = this.dataset.phone;

                document.getElementById('edit_name').value = name;
                document.getElementById('edit_phone').value = phone;
                document.getElementById('editCustomerName').innerText = name;

                document.getElementById('editCustomerForm').action =
                    `/customers/${id}`;
            });
        });

        //Excell
        function exportExcel() {
            const table = document.querySelector("table");

            const cloneTable = table.cloneNode(true);

            // حذف عمود Actions
            cloneTable.querySelectorAll("tr").forEach(row => {
                if (row.cells.length > 0) {
                    row.deleteCell(row.cells.length - 1);
                }
            });

            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.table_to_sheet(cloneTable, {
                raw: true
            });

            // RTL
            ws['!dir'] = 'rtl';

            // عرض الأعمدة
            ws['!cols'] = [{
                    wch: 5
                },
                {
                    wch: 20
                },
                {
                    wch: 15
                },
                {
                    wch: 15
                },
                {
                    wch: 15
                },
                {
                    wch: 15
                }
            ];

            XLSX.utils.book_append_sheet(wb, ws, "Customers");
            XLSX.writeFile(wb, "Customers_Dashboard.xlsx");
        }
    </script>

</body>

</html>
