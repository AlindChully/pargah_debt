<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\CryptoHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('auth.user', compact('users'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'password' => 'nullable|min:4',
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            $user->password = CryptoHelper::encryptStrong($request->password);
        }

        $user->save();

        return back()->with('success', 'User updated successfully');
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|unique:users,name,' . $user->id,
            'phone' => 'required|unique:users,phone,' . $user->id,
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;

        $user->save();

        return back()->with('success', 'Profile updated successfully');
    }

    // إضافة مستخدم جديد
    public function store(Request $request)
    {
        if (Auth::user()->type !== 'super admin') {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'phone' => ['required', 'string', 'max:255', 'unique:users,phone'],
            'password' => 'required|min:4',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => CryptoHelper::encryptStrong($request->password),
            'type' => 'user',
        ]);

        event(new Registered($user));

        return back()->with('success', 'User added successfully');
    }
}