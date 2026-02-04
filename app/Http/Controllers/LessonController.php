<?php

namespace App\Http\Controllers;

use App\Models\Competency;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\SchoolYear;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function index(Request $request): View
    {
        $query = Lesson::query()
            ->with(['subject', 'grade', 'competency', 'schoolYear', 'creator'])
            ->orderByDesc('created_at');

        $subjectId = $request->integer('subject_id');
        $gradeId = $request->integer('grade_id');
        $schoolYearId = $request->integer('school_year_id');

        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        if ($gradeId) {
            $query->where('grade_id', $gradeId);
        }

        if ($schoolYearId) {
            $query->where('school_year_id', $schoolYearId);
        }

        $lessons = $query->get();

        return view('lessons.index', [
            'lessons' => $lessons,
            'subjects' => Subject::query()->orderBy('name')->get(),
            'grades' => Grade::query()->orderBy('level')->get(),
            'schoolYears' => SchoolYear::query()->orderByDesc('is_active')->orderByDesc('start_date')->get(),
            'filters' => [
                'subject_id' => $subjectId,
                'grade_id' => $gradeId,
                'school_year_id' => $schoolYearId,
            ],
        ]);
    }

    public function create(): View
    {
        return view('lessons.create', [
            'subjects' => Subject::query()->orderBy('name')->get(),
            'grades' => Grade::query()->orderBy('level')->get(),
            'competencies' => Competency::query()->with(['subject', 'grade'])->orderBy('code')->get(),
            'schoolYears' => SchoolYear::query()->orderByDesc('is_active')->orderByDesc('start_date')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'grade_id' => ['required', 'exists:grades,id'],
            'competency_id' => ['nullable', 'exists:competencies,id'],
            'school_year_id' => ['required', 'exists:school_years,id'],
            'objectives' => ['nullable', 'string'],
            'materials' => ['nullable', 'string'],
            'procedure' => ['nullable', 'string'],
            'assessment_method' => ['nullable', 'string'],
        ]);

        $validated['created_by'] = Auth::id();

        Lesson::create($validated);

        return redirect()->route('lessons.index');
    }

    public function edit(Lesson $lesson): View
    {
        return view('lessons.edit', [
            'lesson' => $lesson->load(['resources']),
            'subjects' => Subject::query()->orderBy('name')->get(),
            'grades' => Grade::query()->orderBy('level')->get(),
            'competencies' => Competency::query()->with(['subject', 'grade'])->orderBy('code')->get(),
            'schoolYears' => SchoolYear::query()->orderByDesc('is_active')->orderByDesc('start_date')->get(),
        ]);
    }

    public function update(Request $request, Lesson $lesson): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'grade_id' => ['required', 'exists:grades,id'],
            'competency_id' => ['nullable', 'exists:competencies,id'],
            'school_year_id' => ['required', 'exists:school_years,id'],
            'objectives' => ['nullable', 'string'],
            'materials' => ['nullable', 'string'],
            'procedure' => ['nullable', 'string'],
            'assessment_method' => ['nullable', 'string'],
        ]);

        if ($conflict = $this->ensureNoConflict($request, $lesson)) {
            return $conflict;
        }

        $lesson->update($validated);

        return redirect()->route('lessons.index');
    }

    public function destroy(Lesson $lesson): RedirectResponse
    {
        $lesson->delete();

        return redirect()->route('lessons.index');
    }
}
