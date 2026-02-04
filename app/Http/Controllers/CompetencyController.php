<?php

namespace App\Http\Controllers;

use App\Models\Competency;
use App\Models\Grade;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CompetencyController extends Controller
{
    public function index(Request $request): View
    {
        $query = Competency::query()
            ->with(['subject', 'grade'])
            ->orderBy('code');

        $subjectId = $request->integer('subject_id');
        $gradeId = $request->integer('grade_id');

        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        if ($gradeId) {
            $query->where('grade_id', $gradeId);
        }

        $competencies = $query->get();

        return view('competencies.index', [
            'competencies' => $competencies,
            'subjects' => Subject::query()->orderBy('name')->get(),
            'grades' => Grade::query()->orderBy('level')->get(),
            'filters' => [
                'subject_id' => $subjectId,
                'grade_id' => $gradeId,
            ],
        ]);
    }

    public function create(): View
    {
        return view('competencies.create', [
            'subjects' => Subject::query()->orderBy('name')->get(),
            'grades' => Grade::query()->orderBy('level')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'grade_id' => ['required', 'exists:grades,id'],
            'code' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string', 'max:2000'],
            'matatag_tag' => ['required', 'string', 'max:50'],
        ]);

        $request->validate([
            'code' => [
                Rule::unique('competencies', 'code')
                    ->where(fn ($query) => $query
                        ->where('subject_id', $validated['subject_id'])
                        ->where('grade_id', $validated['grade_id'])
                    ),
            ],
        ]);

        Competency::create($validated);

        return redirect()->route('competencies.index');
    }

    public function edit(Competency $competency): View
    {
        return view('competencies.edit', [
            'competency' => $competency,
            'subjects' => Subject::query()->orderBy('name')->get(),
            'grades' => Grade::query()->orderBy('level')->get(),
        ]);
    }

    public function update(Request $request, Competency $competency): RedirectResponse
    {
        $validated = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'grade_id' => ['required', 'exists:grades,id'],
            'code' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string', 'max:2000'],
            'matatag_tag' => ['required', 'string', 'max:50'],
        ]);

        $request->validate([
            'code' => [
                Rule::unique('competencies', 'code')
                    ->where(fn ($query) => $query
                        ->where('subject_id', $validated['subject_id'])
                        ->where('grade_id', $validated['grade_id'])
                    )
                    ->ignore($competency->id),
            ],
        ]);

        if ($conflict = $this->ensureNoConflict($request, $competency)) {
            return $conflict;
        }

        $competency->update($validated);

        return redirect()->route('competencies.index');
    }

    public function destroy(Competency $competency): RedirectResponse
    {
        $competency->delete();

        return redirect()->route('competencies.index');
    }
}
