<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        // Apply filters
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active' ? 1 : 0);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }
        
        $users = $query->latest()->paginate(15)->appends($request->except('page'));
        
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'role' => 'required|in:admin,librarian,member',
            'wallet_balance' => 'nullable|numeric|min:0',
            'outstanding_debt' => 'nullable|numeric|min:0',
        ]);

        $validated['password'] = password_hash($validated['password'], PASSWORD_BCRYPT);
        $validated['wallet_balance'] = $validated['wallet_balance'] ?? 0;
        $validated['outstanding_debt'] = $validated['outstanding_debt'] ?? 0;
        User::create($validated);
        
        return redirect()->route('users.index')->with('success', 'Thêm người dùng thành công!');
    }

    public function show($id)
    {
        $user = User::with('borrows.book')->findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'role' => 'required|in:admin,librarian,member',
            'wallet_balance' => 'nullable|numeric|min:0',
            'outstanding_debt' => 'nullable|numeric|min:0',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = password_hash($request->password, PASSWORD_BCRYPT);
        }

        $validated['wallet_balance'] = $validated['wallet_balance'] ?? $user->wallet_balance ?? 0;
        $validated['outstanding_debt'] = $validated['outstanding_debt'] ?? $user->outstanding_debt ?? 0;

        $user->update($validated);
        return redirect()->route('users.index')->with('success', 'Cập nhật người dùng thành công!');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('users.index')->with('success', 'Xóa người dùng thành công!');
    }
}
