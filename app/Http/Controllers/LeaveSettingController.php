<?php

namespace App\Http\Controllers;

use App\Models\LeaveSetting;
use Illuminate\Http\Request;

class LeaveSettingController extends Controller
{
    public function edit()
    {
        $settings = LeaveSetting::current();

        return view('leave-settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'annual_default' => ['required', 'integer', 'min:0', 'max:365'],
            'sick_default' => ['required', 'integer', 'min:0', 'max:365'],
            'casual_default' => ['required', 'integer', 'min:0', 'max:365'],
        ]);

        $settings = LeaveSetting::current();
        $settings->update($validated);

        return redirect()->route('leave-settings.edit')
            ->with('status', 'Leave settings updated. This applies to all employees immediately.');
    }
}
