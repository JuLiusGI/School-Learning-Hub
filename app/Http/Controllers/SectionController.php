<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SectionController extends Controller
{
    public function index(): View
    {
        $sections = Section::query()
            ->with(['grade', 'adviser'])
            ->orderBy('name')
            ->get();

        return view('sections.index', [
            'sections' => $sections,
        ]);
    }

    public function create(): View
    {
        return view('sections.create', [
            'grades' => Grade::query()->orderBy('level')->get(),
            'advisers' => User::query()
                ->where('role', 'teacher')
                ->where('status', 'active')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'grade_id' => ['required', 'exists:grades,id'],
            'adviser_user_id' => ['nullable', 'exists:users,id'],
        ]);

        $request->validate([
            'name' => [
                Rule::unique('sections', 'name')->where(fn ($query) => $query->where('grade_id', $validated['grade_id'])),
            ],
        ]);

        Section::create($validated);

        return redirect()->route('sections.index');
    }

    public function edit(Section $section): View
    {
        return view('sections.edit', [
            'section' => $section,
            'grades' => Grade::query()->orderBy('level')->get(),
            'advisers' => User::query()
                ->where('role', 'teacher')
                ->where('status', 'active')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(Request $request, Section $section): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'grade_id' => ['required', 'exists:grades,id'],
            'adviser_user_id' => ['nullable', 'exists:users,id'],
        ]);

        $request->validate([
            'name' => [
                Rule::unique('sections', 'name')
                    ->where(fn ($query) => $query->where('grade_id', $validated['grade_id']))
                    ->ignore($section->id),
            ],
        ]);

        if ($conflict = $this->ensureNoConflict($request, $section)) {
            return $conflict;
        }

        $section->update($validated);

        return redirect()->route('sections.index');
    }

    public function destroy(Section $section): RedirectResponse
    {
        $section->delete();

        return redirect()->route('sections.index');
    }
}
