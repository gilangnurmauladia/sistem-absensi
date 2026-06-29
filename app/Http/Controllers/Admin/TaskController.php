<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Employee;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with('employee')
            ->latest()
            ->paginate(10);

        return view('admin.tasks.index', compact('tasks'));
    }

    public function create()
    {
        $employees = Employee::orderBy('name')->get();

        return view('admin.tasks.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'task_name' => 'required|string|max:255',
            'status' => 'required|in:belum,selesai',
            'task_date' => 'required|date',
        ]);

        Task::create($request->only([
            'employee_id',
            'task_name',
            'status',
            'task_date',
        ]));

        return redirect()
            ->route('admin.tasks.index')
            ->with('success', 'Tugas karyawan berhasil ditambahkan.');
    }

    public function edit(Task $task)
    {
        $employees = Employee::orderBy('name')->get();

        return view('admin.tasks.edit', compact('task', 'employees'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'task_name' => 'required|string|max:255',
            'status' => 'required|in:belum,selesai',
            'task_date' => 'required|date',
        ]);

        $task->update($request->only([
            'employee_id',
            'task_name',
            'status',
            'task_date',
        ]));

        return redirect()
            ->route('admin.tasks.index')
            ->with('success', 'Tugas karyawan berhasil diperbarui.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()
            ->route('admin.tasks.index')
            ->with('success', 'Tugas karyawan berhasil dihapus.');
    }
}