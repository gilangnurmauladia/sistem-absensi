<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with('user')->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('employee_code', 'like', '%' . $request->search . '%')
                  ->orWhere('position', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $employees = $query->paginate(10)->withQueryString();

        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        $positions = ['Barista', 'Kasir', 'Waiters', 'Kitchen', 'Supervisor', 'Store Manager'];
        return view('admin.employees.create', compact('positions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:6',
            'position'      => 'required|string',
            'phone'         => 'nullable|string|max:20',
            'address'       => 'nullable|string',
            'join_date'     => 'required|date',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Buat user akun
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'karyawan',
        ]);

        // Generate employee code
        $lastEmployee = Employee::orderBy('id', 'desc')->first();
        $nextNumber   = $lastEmployee ? ($lastEmployee->id + 1) : 1;
        $employeeCode = 'KRY' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Handle foto
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('employees', 'public');
        }

        Employee::create([
            'user_id'       => $user->id,
            'employee_code' => $employeeCode,
            'name'          => $request->name,
            'position'      => $request->position,
            'phone'         => $request->phone,
            'address'       => $request->address,
            'photo'         => $photoPath,
            'join_date'     => $request->join_date,
            'status'        => 'aktif',
        ]);

        return redirect()->route('admin.employees.index')
            ->with('success', "Karyawan {$request->name} berhasil ditambahkan.");
    }

    public function show(Employee $employee)
    {
        $employee->load(['attendances' => fn($q) => $q->latest()->take(10), 'leaves', 'performanceReviews.reviewedBy']);
        return view('admin.employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $positions = ['Barista', 'Kasir', 'Waiters', 'Kitchen', 'Supervisor', 'Store Manager'];
        return view('admin.employees.edit', compact('employee', 'positions'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($employee->user_id)],
            'position' => 'required|string',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string',
            'join_date'=> 'required|date',
            'status'   => 'required|in:aktif,nonaktif',
            'photo'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update user
        $userData = ['name' => $request->name, 'email' => $request->email];
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        $employee->user->update($userData);

        // Handle foto baru
        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $photoPath = $request->file('photo')->store('employees', 'public');
            $employee->photo = $photoPath;
        }

        $employee->update([
            'name'     => $request->name,
            'position' => $request->position,
            'phone'    => $request->phone,
            'address'  => $request->address,
            'join_date'=> $request->join_date,
            'status'   => $request->status,
            'photo'    => $employee->photo,
        ]);

        return redirect()->route('admin.employees.index')
            ->with('success', "Data karyawan {$request->name} berhasil diperbarui.");
    }

    public function destroy(Employee $employee)
    {
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }
        $employee->user->delete(); // cascade delete employee via FK

        return redirect()->route('admin.employees.index')
            ->with('success', 'Karyawan berhasil dihapus.');
    }
}
