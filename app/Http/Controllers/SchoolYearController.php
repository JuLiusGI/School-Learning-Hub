<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SchoolYearController extends Controller
{
    public function index(): View
    {
        $schoolYears = SchoolYear::query()
            ->orderByDesc('start_date')
            ->get();

        return view('school_years.index', [
            'schoolYears' => $schoolYears,
        ]);
    }

    public function create(): View
    {
        return view('school_years.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:20', 'unique:school_years,name'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $schoolYear = SchoolYear::create([
            'name' => $validated['name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        if ($schoolYear->is_active) {
            SchoolYear::whereKeyNot($schoolYear->id)->update(['is_active' => false]);
        }

        return redirect()->route('school-years.index');
    }

    public function edit(SchoolYear $schoolYear): View
    {
        return view('school_years.edit', [
            'schoolYear' => $schoolYear,
        ]);
    }

    public function update(Request $request, SchoolYear $schoolYear): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:20', Rule::unique('school_years', 'name')->ignore($schoolYear->id)],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $schoolYear->update([
            'name' => $validated['name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        if ($schoolYear->is_active) {
            SchoolYear::whereKeyNot($schoolYear->id)->update(['is_active' => false]);
        }

        return redirect()->route('school-years.index');
    }

    public function destroy(SchoolYear $schoolYear): RedirectResponse
    {
        $schoolYear->delete();

        return redirect()->route('school-years.index');
    }
}
