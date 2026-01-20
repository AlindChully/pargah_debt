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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
