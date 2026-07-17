<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        $request->session()->regenerate();

        $destination = Auth::user()->isAdmin()
            ? route('dashboard')
            : route('employees.dashboard');

        return redirect()->intended($destination)
            ->with('status', 'Welcome back, '.Auth::user()->name.'.');
    }

    public function showRegister()
    {
        $departments = Department::orderBy('name')->get();

        return view('auth.register', compact('departments'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'department_id' => ['required', 'exists:departments,id'],
            'designation' => ['required', 'string', 'max:100'],
        ]);

        $user = DB::transaction(function () use ($validated) {
            $isFirstUser = User::query()->count() === 0;

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $isFirstUser ? 'admin' : 'employee',
            ]);

            // Admin ka apna alag HR record nahi banta — sirf employees ka banta hea.
            if (! $isFirstUser) {
                Employee::create([
                    'user_id' => $user->id,
                    'employee_code' => $this->generateEmployeeCode(),
                    'department_id' => $validated['department_id'],
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'designation' => $validated['designation'],
                    'salary' => 0,
                    'joining_date' => now()->toDateString(),
                    'status' => 'active',
                ]);
            }

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        $destination = $user->isAdmin() ? route('dashboard') : route('employees.dashboard');

        return redirect($destination)
            ->with('status', 'Account created. Welcome to the team, '.$user->name.'.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'You have been signed out.');
    }

    private function generateEmployeeCode(): string
    {
        do {
            $code = 'EMP-'.strtoupper(Str::random(5));
        } while (Employee::where('employee_code', $code)->exists());

        return $code;
    }
}
