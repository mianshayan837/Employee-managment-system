<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        return view('employees.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);
        $validated['employee_code'] = $this->generateCode();

        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')
                ->store('employees', 'public');
        }

        Employee::create($validated);

        return redirect()->route('employees.index')
            ->with('status', 'Employee '.$validated['name'].' has been added.');
    }

    public function show(Employee $employee)
    {
        $employee->load('department');

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::orderBy('name')->get();

        return view('employees.edit', compact('employee', 'departments'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $this->validated($request, $employee->id);

        if ($request->hasFile('profile_image')) {
            if ($employee->profile_image) {
                Storage::disk('public')->delete($employee->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')
                ->store('employees', 'public');
        }

        $employee->update($validated);

        return redirect()->route('employees.index')
            ->with('status', 'Employee '.$employee->name.' has been updated.');
    }

    /**
     * Soft delete — record database se nahi udta, sirf hide ho jata hea.
     */
    public function destroy(Employee $employee)
    {
        $name = $employee->name;

        $employee->delete();

        return redirect()->route('employees.index')
            ->with('status', 'Employee '.$name.' has been moved to trash.');
    }

    /**
     * Trashed (soft-deleted) employees ki list dikhata hea.
     */
    public function trashed()
    {
        $employees = Employee::onlyTrashed()
            ->with('department')
            ->orderByDesc('deleted_at')
            ->paginate(10);

        return view('employees.trashed', compact('employees'));
    }

    /**
     * Ek soft-deleted employee ko wapas active kar deta hea.
     */
    public function restore($id)
    {
        $employee = Employee::onlyTrashed()->findOrFail($id);
        $employee->restore();

        return redirect()->route('employees.trashed')
            ->with('status', 'Employee '.$employee->name.' has been restored.');
    }

    /**
     * Employee ko hamesha ke liye delete kar deta hea (image bhi hata deta hea).
     */
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

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:employees,email,'.$ignoreId],
            'phone' => ['nullable', 'string', 'max:30'],
            'department_id' => ['required', 'exists:departments,id'],
            'designation' => ['required', 'string', 'max:100'],
            'salary' => ['required', 'numeric', 'min:0'],
            'joining_date' => ['required', 'date'],
            'status' => ['required', 'in:active,inactive'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
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