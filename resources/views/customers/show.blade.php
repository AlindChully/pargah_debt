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

    <!-- Modal إضافة دين -->
    <div class="modal fade" id="addDebtModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fw-bold mb-0">{{ __('general.add new debt') }}</h5>
                    <button type="button" class="custom-close-btn" data-bs-dismiss="modal" aria-label="إغلاق">✖</button>
                </div>
                <form action="{{ route('customers.debts.store', $customer) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>{{ __('general.amount') }}</label>
                                <input type="number" name="amount" class="form-control" placeholder="Enter amount"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label>{{ __('general.quantity') }}</label>
                                <input type="number" name="quantity" class="form-control" min="1" value="1" required>
                            </div>
                            <div class="col-md-6">
                                <label>{{ __('general.description') }}</label>
                                <input type="text" name="description" class="form-control" placeholder="Optional">
                            </div>
                            <div class="col-md-6">
                                <label>{{ __('general.notes') }}</label>
                                <textarea name="notes" class="form-control" placeholder="Optional"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label>{{ __('general.debt date') }}</label>
                                <input type="datetime-local" name="debt_date" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary w-100">💾 {{ __('general.add debt') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal تعديل دين -->
    <div class="modal fade" id="editDebtModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fw-bold mb-0">✏️ {{ __('general.debt modification') }}</h5>
                    <button type="button" class="custom-close-btn" data-bs-dismiss="modal" aria-label="إغلاق">✖</button>
                </div>
                <form id="editDebtForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>{{ __('general.amount') }}</label>
                                <input type="number" name="amount" id="edit_amount" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>{{ __('general.quantity') }}</label>
                                <input type="number" name="quantity" id="edit_quantity" class="form-control" min="1"
                                    value="1" required>
                            </div>
                            <div class="col-md-6">
                                <label>{{ __('general.description') }}</label>
                                <input type="text" name="description" id="edit_description" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label>{{ __('general.notes') }}</label>
                                <textarea name="notes" id="edit_notes" class="form-control"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label>{{ __('general.debt date') }}</label>
                                <input type="datetime-local" name="debt_date" id="edit_debt_date" class="form-control"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success w-100">💾 {{ __('general.update debt') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal وصل قبض -->
    <div class="modal fade" id="receiptModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('receipts.store', $customer) }}" id="receiptForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="modal-title">{{ __('general.receipt received') }}</h5>
                        <button type="button" class="custom-close-btn" data-bs-dismiss="modal"
                            aria-label="إغلاق">✖</button>
                    </div>

                    <div class="modal-body">
                        <label>{{ __('general.amount') }}</label>
                        <input type="number" name="amount" class="form-control" required>

                        <label class="mt-2">{{ __('general.date of arrival') }}</label>
                        <input type="datetime-local" name="receipt_date" class="form-control" required>

                        <label class="mt-2">{{ __('general.comments') }}</label>
                        <textarea name="notes" class="form-control"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success w-100">💾
                            {{ __('general.save the receipt') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="body">
        <h2>{{ ucfirst($customer->name) }} / <span class="ltr" dir="ltr">{{ $customer->formatted_phone }}</span></h2>

        <!-- زر القائمة للهواتف فقط -->
        <button class="btn btn3D mb-3 menu" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileActions"
            aria-controls="mobileActions">
            ☰ {{ __('general.menu') }}
        </button>
        <button class="btn btn3D mb-3" data-bs-toggle="modal" data-bs-target="#addDebtModal">
            {{ __('general.add new debt') }}
        </button>

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
                <button class="btn offcanvas-btn mb-2" data-bs-toggle="modal" data-bs-target="#receiptModal">
                    <i class="bi bi-cash-coin"></i> {{ __('general.receipt received') }}
                </button>
                <a href="{{ route('customers.debts.pdf', [
                    'customer' => $customer->id,
                    'from_date' => request('from_date'),
                    'to_date' => request('to_date')
                ]) }}" class="btn offcanvas-btn mb-2">
                    <i class="bi bi-file-earmark-pdf"></i> PDF
                </a>
                <a href="{{ route('customers.debts.print', [
                    'customer' => $customer->id,
                    'status' => request('status'),
                    'from_date' => request('from_date'),
                    'to_date' => request('to_date'),
                ]) }}" target="_blank" class="btn offcanvas-btn mb-2">
                    <i class="bi bi-printer"></i> {{ __('general.print') }}
                </a>
                <button onclick="exportExcel()" class="btn offcanvas-btn mb-2">
                    <i class="bi bi-file-earmark-excel"></i> {{ __('general.excel') }}
                </button>
                <a href="{{ route('dashboard') }}" class="btn offcanvas-btn mb-2">
                    <i class="bi bi-box-arrow-right"></i> {{ __('general.back') }}
                </a>
            </div>
        </div>

        <button class="btn btn-success btn3D mb-3 d-none d-md-inline-block" data-bs-toggle="modal"
            data-bs-target="#receiptModal">
            <i class="bi bi-cash-coin"></i> {{ __('general.receipt received') }}
        </button>
        <a href="{{ route('customers.debts.print', [
                    'customer' => $customer->id,
                    'status' => request('status'),
                    'from_date' => request('from_date'),
                    'to_date' => request('to_date'),
                ]) }}" target="_blank" class="btn btn-dark btn3D mb-3 d-none d-md-inline-block">
            <i class="bi bi-printer"></i> {{ __('general.print') }}
        </a>
        <a href="{{ route('customers.debts.pdf', [
                    'customer' => $customer->id,
                    'from_date' => request('from_date'),
                    'to_date' => request('to_date')
                ]) }}" class="btn btn-success btn3D mb-3 d-none d-md-inline-block">
            <i class="bi bi-file-earmark-pdf"></i> PDF
        </a>
        <button onclick="exportExcel()" class="btn btn-success btn3D mb-3 d-none d-md-inline-block">
            <i class="bi bi-file-earmark-excel"></i> {{ __('general.excel') }}
        </button>
        <a href="{{ route('dashboard') }}" class="btn btn-success btn3D mb-3 d-none d-md-inline-block">
            <i class="bi bi-box-arrow-right"></i> {{ __('general.back') }}
        </a>

        <!-- Filter & Sort Form for phone -->
        <div class="d-md-none mb-3">

            <div class="dropdown w-100">
                <button class="btn btn-primary w-100 btn3D dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-funnel"></i> {{ __('general.filter & sort') }}
                </button>

                <div class="dropdown-menu p-3 w-100">

                    <form method="GET">

                        <!-- Status -->
                        <div class="mb-2">
                            <label class="form-label">{{ __('general.status') }}</label>
                            <select name="status" class="form-select">
                                <option value="">{{ __('general.all') }}</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>
                                    {{ __('general.paid') }}
                                </option>
                                <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>
                                    {{ __('general.unpaid') }}
                                </option>
                            </select>
                        </div>

                        <!-- From -->
                        <div class="mb-2">
                            <label class="form-label">{{ __('general.from date') }}</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>

                        <!-- To -->
                        <div class="mb-2">
                            <label class="form-label">{{ __('general.to date') }}</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>

                        <!-- Min Qty -->
                        <div class="mb-2">
                            <label class="form-label">{{ __('general.min qty') }}</label>
                            <input type="number" name="min_qty" class="form-control" value="{{ request('min_qty') }}">
                        </div>

                        <!-- Sort -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('general.sort') }}</label>
                            <select name="sort" class="form-select">
                                <option value="">{{ __('general.default') }}</option>
                                <option value="date_asc">{{ __('general.first date') }}</option>
                                <option value="date_desc">{{ __('general.last date') }}</option>
                                <option value="amount_asc">{{ __('general.amount') }} ↑</option>
                                <option value="amount_desc">{{ __('general.amount') }} ↓</option>
                                <option value="paid_first">{{ __('general.paid first') }}</option>
                                <option value="unpaid_first">{{ __('general.unpaid first') }}</option>
                            </select>
                        </div>

                        <button class="btn btn-success w-100 mb-2">
                            <i class="bi bi-check-circle"></i> {{ __('general.apply') }}
                        </button>

                        @if(request()->query())
                        <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-outline-danger w-100">
                            <i class="bi bi-x-circle"></i> {{ __('general.clear') }}
                        </a>
                        @endif

                    </form>

                </div>
            </div>
        </div>

        <!-- Filter & Sort Form -->
        <form method="GET" class="row g-3 mb-4 align-items-end d-none d-md-flex">
            <div class="col-md-2">
                <label class="form-label">{{ __('general.status') }}</label>
                <select name="status" class="form-select input3D">
                    <option value="">{{ __('general.all') }}</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>{{ __('general.paid') }}
                    </option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>
                        {{ __('general.unpaid') }}
                    </option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">{{ __('general.from date') }}</label>
                <input type="date" name="from_date" class="form-control input3D" value="{{ request('from_date') }}">
            </div>

            <div class="col-md-2">
                <label class="form-label">{{ __('general.to date') }}</label>
                <input type="date" name="to_date" class="form-control input3D" value="{{ request('to_date') }}">
            </div>

            <div class="col-md-2">
                <label class="form-label">{{ __('general.min qty') }}</label>
                <input type="number" name="min_qty" class="form-control input3D" value="{{ request('min_qty') }}">
            </div>

            <div class="col-md-2">
                <label class="form-label">{{ __('general.sort') }}</label>
                <!-- داخل form الفلاتر -->
                <select name="sort" id="sortSelect" class="form-select input3D">
                    <option value="">{{ __('general.default') }}</option>
                    <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>
                        {{ __('general.first date') }}
                    </option>
                    <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>
                        {{ __('general.last date') }}
                    </option>
                    <option value="amount_asc" {{ request('sort') == 'amount_asc' ? 'selected' : '' }}>
                        {{ __('general.amount') }} ↑
                    </option>
                    <option value="amount_desc" {{ request('sort') == 'amount_desc' ? 'selected' : '' }}>
                        {{ __('general.amount') }} ↓
                    </option>
                    <option value="paid_first" {{ request('sort') == 'paid_first' ? 'selected' : '' }}>
                        {{ __('general.paid first') }}
                    </option>
                    <option value="unpaid_first" {{ request('sort') == 'unpaid_first' ? 'selected' : '' }}>
                        {{ __('general.unpaid first') }}
                    </option>
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn3D primary"><i class="bi bi-funnel"></i> {{ __('general.apply') }}</button>
                @if(request()->query())
                <a href="{{ route('customers.show', $customer->id) }}" class="btn btn3D warning mt-1">
                    <i class="bi bi-x-circle"></i> {{ __('general.clear') }}
                </a>
                @endif
            </div>
        </form>

        <!-- Totals -->
        <div class="row mb-3 text-center">
            <div class="col-md-3">
                <div class="card p-2 total-card total show-page">
                    <small>{{ __('general.total debts') }}</small><strong>{{ $debts->count() }}</strong>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-2 total-card total show-page">
                    <small>{{ __('general.total debts unpaid') }}</small><strong>{{ $debts->where('is_paid', false)->count() }}
                    </strong>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-2 bg-success-subtle total-card show-page">
                    <small>{{ __('general.total amount paid') }}</small>
                    <strong>{{ number_format($paidTotal) }}</strong>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-2 bg-danger-subtle total-card show-page">
                    <small>{{ __('general.total amount unpaid') }}</small><strong>{{ number_format($debts->where('is_paid', false)->sum('amount')) }}</strong>
                </div>
            </div>
        </div>

        <!-- نتائج مع وصل قبض 
        <div class="row text-center mb-3">
            <div class="col-md-4">
                <div class="card p-2 total-card">
                    <small>إجمالي الديون</small>
                    <strong>{{ number_format($totalDebts) }}</strong>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-2 bg-info-subtle total-card">
                    <small>الرصيد الزائد</small>
                    <strong>{{ number_format($balance) }}</strong>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-2 bg-danger-subtle total-card">
                    <small>الدين الحقيقي</small>
                    <strong>{{ number_format($realDebt) }}</strong>
                </div>
            </div>
        </div>
        -->

        <!-- Table -->
        <div class="table-wrapper">
            <table class="table table-hover text-center align-middle teble3D desktop-table show-table">
                <thead style="background-color: #EEEEEE;">
                    <tr>
                        <th>#</th>
                        <th>{{ __('general.date') }}</th>
                        <th>{{ __('general.time') }}</th>
                        <th>{{ __('general.amount') }}</th>
                        <th>{{ __('general.quantity') }}</th>
                        <th>{{ __('general.description') }}</th>
                        <th>{{ __('general.status') }}</th>
                        <th>{{ __('general.notes') }}</th>
                        <th>{{ __('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($debts as $debt)
                    <tr class="{{ $debt->is_paid ? 'table-success' : '' }}">
                        <th>{{ $loop->iteration }}</th>
                        <td>{{ $debt->debt_date->format('Y-m-d') }}</td>
                        <td>{{ $debt->debt_date->format('H:i') }}</td>
                        <td>{{ number_format($debt->amount) }}</td>
                        <td>{{ $debt->quantity }}</td>
                        <td>{{ $debt->description }}</td>
                        <td>
                            <span
                                class="badge {{ $debt->is_paid ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">{{ $debt->is_paid ? 'Paid' : 'Unpaid' }}
                            </span>
                        </td>
                        <td class="note">{{ $debt->notes ?? '-' }}</td>
                        <td>
                            <button type="button" class="btn btn-sm editDebtBtn cardBtn3D warning"
                                data-id="{{ $debt->id }}" data-amount="{{ $debt->amount }}"
                                data-quantity="{{ $debt->quantity }}" data-description="{{ $debt->description }}"
                                data-notes="{{ $debt->notes }}" data-date="{{ $debt->debt_date->format('Y-m-d\TH:i') }}"
                                data-bs-toggle="modal" data-bs-target="#editDebtModal">
                                <i class="bi bi-pencil"></i> {{ __('general.edit') }}
                            </button>
                            <form action="{{ route('debts.destroy', $debt->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Are you sure?');"
                                    class="btn btn-sm cardBtn3D danger"><i class="bi bi-trash"></i>
                                    {{ __('general.delete') }}</button>
                            </form>
                            <!--  
                            <form action="{{ route('debts.toggle', $debt->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="btn btn-sm cardBtn3D info">{{ $debt->is_paid ? 'Mark Unpaid' : 'Mark Paid' }}</button>
                            </form>
                            -->
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-muted">No debts found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Cart -->
    <div class="mobile-cards">
        @foreach($debts as $debt)
        <div class="customer-card">
            <div class="card-header">
                <strong>{{ $debt->description ?? 'Debt' }}</strong>
            </div>

            <div class="card-body">
                <div>
                    <span>{{ __('general.date') }}</span>
                    <b>{{ $debt->debt_date->format('Y-m-d') }}</b>
                </div>

                <div>
                    <span>{{ __('general.time') }}</span>
                    <b>{{ $debt->debt_date->format('H:i') }}</b>
                </div>

                <div>
                    <span>{{ __('general.quantity') }}</span>
                    <b>{{ $debt->quantity }}</b>
                </div>

                <div class="red">
                    <span>{{ __('general.amount') }}</span>
                    <b>{{ number_format($debt->amount) }}</b>
                </div>

                <div>
                    <span>{{ __('general.status') }}</span>
                    <span
                        class="badge {{ $debt->is_paid ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">{{ $debt->is_paid ? 'Paid' : 'Unpaid' }}
                    </span>
                </div>

            </div>

            <div class="card-actions">
                <button class="btn btn-sm cardBtn3D warning editDebtBtn" data-id="{{ $debt->id }}"
                    data-amount="{{ $debt->amount }}" data-quantity="{{ $debt->quantity }}"
                    data-description="{{ $debt->description }}" data-notes="{{ $debt->notes }}"
                    data-date="{{ $debt->debt_date->format('Y-m-d\TH:i') }}" data-bs-toggle="modal"
                    data-bs-target="#editDebtModal">
                    <i class="bi bi-pencil"></i> {{ __('general.edit') }}
                </button>
                <form action="{{ route('debts.destroy', $debt->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Are you sure?');" class="btn btn-sm cardBtn3D danger"><i
                            class="bi bi-trash"></i> {{ __('general.delete') }}</button>
                </form>
                <!-- 
                <form action="{{ route('debts.toggle', $debt->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button class="btn btn-sm cardBtn3D info">
                        {{ $debt->is_paid ? 'Unpaid' : 'Paid' }}
                    </button>
                </form>
                -->
            </div>
        </div>
        @endforeach
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>

    <script>
        // JavaScript لتحميل PDF تلقائيًا وفتح تاب جديد
        document.getElementById('receiptForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(async response => {
                    const text = await response.text(); // 👈 نقرأ أي رد
                    try {
                        return JSON.parse(text);
                    } catch {
                        console.error('❌ Response is not JSON:', text);
                        alert('حدث خطأ في إنشاء PDF');
                        throw new Error('Not JSON');
                    }
                })
                .then(data => {
                    console.log('✅ Response:', data);

                    if (!data.pdf_url) {
                        alert('❌ لم يتم إنشاء ملف PDF');
                        return;
                    }

                    // فتح PDF في تب جديد
                    window.open(data.pdf_url, '_blank');

                    // تحميل تلقائي
                    const a = document.createElement('a');
                    a.href = data.download_url;
                    a.download = data.download_url.split('/').pop();
                    document.body.appendChild(a);
                    a.click();
                    a.remove();

                    // إعادة تحميل الصفحة
                    setTimeout(() => {
                        window.location.reload();
                    }, 800);
                })
                .catch(err => {
                    console.error(err);
                    alert('❌ فشل إنشاء الوصل');
                });
        });

        // يعيد تحميل الصفحة مرة واحدة عند الدخول
        if (!sessionStorage.getItem('refreshed')) {
            sessionStorage.setItem('refreshed', 'true');
            window.location.reload();
        } else {
            sessionStorage.removeItem('refreshed'); // يمسح العلامة بعد إعادة التحميل
        }

        //زرر التعديل
        document.querySelectorAll('.editDebtBtn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                document.getElementById('edit_amount').value = this.dataset.amount;
                document.getElementById('edit_quantity').value = this.dataset.quantity;
                document.getElementById('edit_description').value = this.dataset.description ?? '';
                document.getElementById('edit_notes').value = this.dataset.notes ?? '';
                document.getElementById('edit_debt_date').value = this.dataset.date;
                document.getElementById('editDebtForm').action = `/debts/${id}`;
            });
        });

        // Sort auto-submit
        const sortSelect = document.getElementById('sortSelect');
        const filterForm = sortSelect.closest('form');

        sortSelect.addEventListener('change', () => {
            filterForm.submit(); // فور تغيير Sort، الفورم يُرسل مع كل الفلاتر الحالية
        });

        //Excell
        function exportExcel() {
            const table = document.querySelector("table"); // أول جدول

            // نسخ الجدول حتى لا نغير الأصلي
            const cloneTable = table.cloneNode(true);

            // إزالة آخر عمود من كل صف
            cloneTable.querySelectorAll("tr").forEach(row => {
                if (row.cells.length > 0) {
                    row.deleteCell(row.cells.length - 1); // يحذف آخر خلية في كل صف
                }
            });

            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.table_to_sheet(cloneTable, {
                raw: true
            });

            // RTL
            ws['!dir'] = 'rtl';

            // فرەهیا عامودا
            ws['!cols'] = [{
                    wch: 6
                },
                {
                    wch: 12
                },
                {
                    wch: 12
                },
                {
                    wch: 10
                },
                {
                    wch: 10
                },
                {
                    wch: 20
                },
                {
                    wch: 10
                },
                {
                    wch: 30
                }
            ];

            XLSX.utils.book_append_sheet(wb, ws, "الديون");
            XLSX.writeFile(wb, "Debts_{{ $customer->name }}.xlsx");
        }
    </script>

</body>

</html>
