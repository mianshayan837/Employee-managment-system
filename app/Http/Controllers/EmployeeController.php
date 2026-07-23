<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::search($request->query('q'))
            ->with('department')
            ->when($request->query('department'), fn ($q, $deptId) => $q->where('department_id', $deptId))
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $departments = Department::orderBy('name')->get();

        return view('employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $shifts = \App\Models\Shift::orderBy('start_time')->get();

        return view('employees.create', compact('departments', 'shifts'));
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request, null, true);
        $validated['employee_code'] = $this->generateCode();

        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')
                ->store('employees', 'public');
        }

      
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'employee',
        ]);

        unset($validated['password']);
        $validated['user_id'] = $user->id;

        Employee::create($validated);

        return redirect()->route('employees.index')
            ->with('status', 'Employee '.$validated['name'].' has been added with login access.');
    }

    public function show(Employee $employee)
    {
        $employee->load('department', 'shift');

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::orderBy('name')->get();
        $shifts = \App\Models\Shift::orderBy('start_time')->get();

        return view('employees.edit', compact('employee', 'departments', 'shifts'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $this->validated($request, $employee, false);

        if ($request->hasFile('profile_image')) {
            if ($employee->profile_image) {
                Storage::disk('public')->delete($employee->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')
                ->store('employees', 'public');
        }

        $newPassword = $validated['password'] ?? null;
        unset($validated['password']);

        $employee->update($validated);

        if ($employee->user) {
            $employee->user->update(array_filter([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $newPassword ? Hash::make($newPassword) : null,
            ]));
        }

        return redirect()->route('employees.index')
            ->with('status', 'Employee '.$employee->name.' has been updated.');
    }

    public function destroy(Employee $employee)
    {
        $name = $employee->name;

        $employee->delete();

        return redirect()->route('employees.index')
            ->with('status', 'Employee '.$name.' has been moved to trash.');
    }

    public function trashed()
    {
        $employees = Employee::onlyTrashed()
            ->with('department')
            ->orderByDesc('deleted_at')
            ->paginate(10);

        return view('employees.trashed', compact('employees'));
    }

    public function restore($id)
    {
        $employee = Employee::onlyTrashed()->findOrFail($id);
        $employee->restore();

        return redirect()->route('employees.trashed')
            ->with('status', 'Employee '.$employee->name.' has been restored.');
    }

    public function forceDelete($id)
    {
        $employee = Employee::onlyTrashed()->findOrFail($id);
        $name = $employee->name;

        if ($employee->profile_image) {
            Storage::disk('public')->delete($employee->profile_image);
        }

        $employee->forceDelete();

        return redirect()->route('employees.trashed')
            ->with('status', 'Employee '.$name.' has been permanently deleted.');
    }

    private function validated(Request $request, ?Employee $employee = null, bool $requirePassword = false): array
    {
        $ignoreId = $employee?->id;
        $ignoreUserId = $employee?->user_id;

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('employees', 'email')->ignore($ignoreId),
                Rule::unique('users', 'email')->ignore($ignoreUserId),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'department_id' => ['required', 'exists:departments,id'],
            'shift_id' => ['required', 'exists:shifts,id'],
            'designation' => ['required', 'string', 'max:100'],
            'salary' => ['required', 'numeric', 'min:0'],
            'joining_date' => ['required', 'date'],
            'status' => ['required', 'in:active,inactive'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'password' => [$requirePassword ? 'required' : 'nullable', 'string', 'min:8'],
        ]);
    }

    private function generateCode(): string
    {
        do {
            $code = 'EMP-'.strtoupper(Str::random(5));
        } while (Employee::where('employee_code', $code)->exists());

        return $code;
    }
}