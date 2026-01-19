<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('general.login') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/employeepage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="/css/three-d.css">
    <link rel="stylesheet" href="{{ asset('css/lang.css') }}">
    <style>
        body {
            background-color: rgb(240, 240, 240);
        }

        div.card.cart {
            border: none !important;
            color: #000 !important;
            box-shadow: 10px 10px 10px rgba(10, 99, 169, 0.16),
                -10px -10px 10px rgba(223, 220, 220, 0.7) !important;
        }
    </style>
</head>

<body>
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

    <div class="container">
        <div class="row justify-content-center align-items-center" style="height:100vh;">
            <div class="col-md-5">
                <div class="card shadow-sm cart">
                    <div class="card-body p-4">

                        <!-- Logo -->
                        <div class="text-center mb-4">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width:160px; height: 160px;">
                        </div>

                        <h1 class="card-title text-center mb-4">{{ __('general.login') }}</h1>

                        @if(session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('general.user name') }}</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                                    required autofocus>
                                @error('name')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('general.password') }}</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                @error('password')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn3D">
                                    {{ __('general.login') }}
                                </button>
                            </div>
                        </form>

                        <div class="mt-3 text-center">
                            <p style="font-weight: bold;">@ By Alind Chuly Software</p>
                            <button class="btn btn-primary btn3D mb-3 d-none d-md-inline-block" data-bs-toggle="modal"
            data-bs-target="#addUserModal">
            <i class="bi bi-person-plus"></i> {{ __('general.add user') }}
        </button>
                            <a href="{{ route('users.index') }}" target="_blank"
            class="btn btn-primary btn3D mb-3 d-none d-md-inline-block">
            <i class="bi bi-person"></i> {{ __('general.users') }}
        </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
