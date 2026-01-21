<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['ar','krd']) ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'تقرير الزبائن' }}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    <style>
        body {
            background: #f8f9fa;
            font-family: Tahoma, Arial, sans-serif;
        }

        .report-box {
            background: #fff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 0 15px rgba(0, 0, 0, .08);
        }

        .report-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .report-period {
            font-size: 14px;
            color: #777;
        }

        .card i {
            opacity: .8;
        }

        .ltr {
            direction: ltr;
            unicode-bidi: bidi-override;
            display: inline-block;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: #fff;
            }

            .report-box {
                box-shadow: none;
                padding: 0;
            }
        }

        @media (max-width: 767px) and (orientation: portrait) {

            .report-box {
                padding: 10px;
            }

            table {
                font-size: 10px !important;
            }

        }

        @media (max-width: 932px) and (orientation: landscape) {

            table {
                font-size: 12px !important;
            }

        }
    </style>
</head>

<body>

    <div class="container my-4">

        {{-- ===== نموذج التقرير ===== --}}
        <div class="report-box mb-4 no-print">
            <form method="GET" action="{{ route('reports.customers') }}" class="row g-3 align-items-end">

                <div class="col-md-3">
                    <label class="form-label fw-bold">{{ __('general.report type') }}</label>
                    <select name="type" class="form-select" onchange="this.form.submit()">
                        <option value="monthly" {{ request('type')=='monthly'?'selected':'' }}>
                            {{ __('general.monthly report') }}
                        </option>
                        <option value="yearly" {{ request('type')=='yearly'?'selected':'' }}>
                            {{ __('general.annual report') }}
                        </option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">
                        {{ request('type','monthly')=='monthly' ? __('general.month') : __('general.year') }}
                    </label>

                    @if(request('type','monthly')=='monthly')
                    <input type="month" name="month" class="form-control"
                        value="{{ request('month', now()->format('Y-m')) }}">
                    @else
                    <input type="number" name="year" class="form-control" value="{{ request('year', now()->year) }}"
                        min="2020">
                    @endif
                </div>

                <div class="col-md-3">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-bar-chart"></i> {{ __('general.present the report') }}
                    </button>
                </div>

            </form>

        </div>

        @php
        $title = request('type', 'monthly') === 'monthly'
        ? __('general.monthly report')
        : __('general.annual report');
        @endphp

        {{-- ===== التقرير ===== --}}
        <div class="report-box">

            {{-- عنوان --}}
            <div class="text-center mb-4">
                <h3 class="report-title">{{ $title ?? 'تقرير الزبائن' }}</h3>
                <br>
                <div class="report-period">
                    <h5>{{ __('general.from') }} {{ $from->format('Y-m-d') }} {{ __('general.to') }}
                        {{ $to->format('Y-m-d') }}
                    </h5>
                </div>
                <br>
                <div class="text-center mb-3 no-print">
                    <a href="{{ route('reports.customers.print', request()->query()) }}" target="_blank"
                        class="btn btn-primary">
                        <i class="bi bi-printer"></i> {{ __('general.print the report') }}
                    </a>

                    <a href="{{ route('reports.customers.pdf', request()->query()) }}" class="btn btn-primary">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                    </a>
                </div>
            </div>

            {{-- ملخص --}}
            <div class="row text-center mb-4">

                <div class="col-md-4 mb-2">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-exclamation-circle text-danger fs-3"></i>
                            <p class="mb-1">{{ __('general.number of outstanding debts') }}</p>
                            <h5 class="fw-bold">
                                {{ $customers->sum(fn($c)=>$c->debts->where('is_paid',false)->count()) }}
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-2">
                    <div class="card border-0 shadow-sm bg-success-subtle">
                        <div class="card-body">
                            <i class="bi bi-cash-stack text-success fs-3"></i>
                            <p class="mb-1">{{ __('general.total received') }}</p>
                            <h5 class="fw-bold">{{ number_format($paidTotal) }}</h5>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-2">
                    <div class="card border-0 shadow-sm bg-danger-subtle">
                        <div class="card-body">
                            <i class="bi bi-wallet2 text-danger fs-3"></i>
                            <p class="mb-1">{{ __('general.total unpaid') }}</p>
                            <h5 class="fw-bold">
                                {{ number_format(
                                $customers->sum(fn($c)=>$c->debts->where('is_paid',false)->sum('amount'))
                            ) }}
                            </h5>
                        </div>
                    </div>
                </div>

            </div>

            {{-- جدول --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('general.name') }}</th>
                            <th>{{ __('general.phone') }}</th>
                            <th>{{ __('general.unpaid debt') }}</th>
                            <th>{{ __('general.unpaid amount') }}</th>
                            <th>{{ __('general.paid amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>
                                <span class="ltr fw-bold">
                                    {{ $customer->formatted_phone }}
                                </span>
                            </td>
                            <td>{{ $customer->debts->where('is_paid',false)->count() }}</td>
                            <td class="text-danger fw-bold">
                                {{ number_format($customer->debts->where('is_paid',false)->sum('amount')) }}
                            </td>
                            <td class="text-success fw-bold">
                                {{ number_format($customer->receipts->sum('amount')) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-muted">{{ __('general.no data available') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

</body>

</html>
