<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function index(): View
    {
        $grades = Grade::query()
            ->orderBy('level')
            ->get();

        return view('grades.index', [
            'grades' => $grades,
        ]);
    }

    public function create(): View
    {
        return view('grades.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'level' => ['required', 'string', 'max:50', 'unique:grades,level'],
        ]);

        Grade::create($validated);

        return redirect()->route('grades.index');
    }

    public function edit(Grade $grade): View
    {
        return view('grades.edit', [
            'grade' => $grade,
        ]);
    }

    public function update(Request $request, Grade $grade): RedirectResponse
    {
        $validated = $request->validate([
            'level' => ['required', 'string', 'max:50', Rule::unique('grades', 'level')->ignore($grade->id)],
        ]);

        if ($conflict = $this->ensureNoConflict($request, $grade)) {
            return $conflict;
        }

        $grade->update($validated);

        return redirect()->route('grades.index');
    }

    public function destroy(Grade $grade): RedirectResponse
    {
        $grade->delete();

        return redirect()->route('grades.index');
    }
}
