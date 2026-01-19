<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Users' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/employeepage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/three-d.css') }}">

    <style>
    @media (max-width: 768px) {

        th,
        td {
            font-size: 10px;
        }

        /* اخفاء نص الأزرار في الأعمدة Actions */
        .action button i+span,
        .action button {
            font-size: 0;
        }

        /* يمكن إبقاء الأيقونة فقط */
        .action button i {
            font-size: 10px;
        }
    }
    </style>
</head>

<body>

    
    <div class="body m-4">
        <h3 class="mb-3">{{ __('general.users page') }}</h3>

        <div class="">
            <table class="table table-hover text-center align-middle teble3D">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>{{ __('general.name') }}</th>
                        <th>{{ __('general.phone') }}</th>
                        <th>{{ __('general.password') }}</th>
                        <th>{{ __('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ ucfirst($user->name) }}</td>
                        <td dir="ltr">{{ $user->phone }}</td>
                        <td>{{ \App\Helpers\CryptoHelper::decryptStrong($user->password) }}</td>
                        <td class="action">
                            <button type="button" class="btn btn-sm editUserBtn cardBtn3D warning"
                                data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-phone="{{ $user->phone }}"
                                data-password="{{ \App\Helpers\CryptoHelper::decryptStrong($user->password) }}"
                                data-bs-toggle="modal" data-bs-target="#editUserModal">
                                <i class="bi bi-pencil"></i> {{ __('general.edit') }}
                            </button>

                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟');"
                                    class="btn btn-sm cardBtn3D danger">
                                    <i class="bi bi-trash"></i> {{ __('general.delete') }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <script>
    //Edit button
    document.querySelectorAll('.editUserBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const phone = this.dataset.phone;
            const password = this.dataset.password;

            document.getElementById('edit_name').value = name;
            document.getElementById('edit_phone').value = phone;
            document.getElementById('edit_password').value = password;

            document.getElementById('editUserForm').action = `/users/${id}`;
        });
    });
    </script>

</body>

</html>
