<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::search($request->query('q'))
            ->withCount('employees')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        Department::create($validated);

        return redirect()->route('departments.index')
            ->with('status', 'Department '.$validated['name'].' has been added.');
    }

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $this->validated($request, $department->id);

        $department->update($validated);

        return redirect()->route('departments.index')
            ->with('status', 'Department '.$department->name.' has been updated.');
    }

    public function destroy(Department $department)
    {
        if ($department->employees()->exists()) {
            return redirect()->route('departments.index')
                ->with('status', 'Cannot remove '.$department->name.' — it still has employees assigned.');
        }

        $name = $department->name;
        $department->delete();

        return redirect()->route('departments.index')
            ->with('status', 'Department '.$name.' has been removed.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:departments,name,'.$ignoreId],
            'code' => ['required', 'string', 'max:20', 'unique:departments,code,'.$ignoreId],
            'description' => ['nullable', 'string', 'max:500'],
        ]);
    }
}
