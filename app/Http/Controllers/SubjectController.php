<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(): View
    {
        $subjects = Subject::query()
            ->orderBy('name')
            ->get();

        return view('subjects.index', [
            'subjects' => $subjects,
        ]);
    }

    public function create(): View
    {
        return view('subjects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:subjects,name'],
        ]);

        Subject::create($validated);

        return redirect()->route('subjects.index');
    }

    public function edit(Subject $subject): View
    {
        return view('subjects.edit', [
            'subject' => $subject,
        ]);
    }

    public function update(Request $request, Subject $subject): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('subjects', 'name')->ignore($subject->id)],
        ]);

        if ($conflict = $this->ensureNoConflict($request, $subject)) {
            return $conflict;
        }

        $subject->update($validated);

        return redirect()->route('subjects.index');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        $subject->delete();

        return redirect()->route('subjects.index');
    }
}
