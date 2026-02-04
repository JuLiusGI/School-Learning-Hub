<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Enrollment;
use App\Models\Score;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ScoreController extends Controller
{
    public function index(Request $request): View
    {
        $query = Score::query()
            ->with(['assessment.lesson.subject', 'assessment.section.grade', 'student'])
            ->orderByDesc('created_at');

        $assessmentId = $request->integer('assessment_id');

        if ($assessmentId) {
            $query->where('assessment_id', $assessmentId);
        }

        $scores = $query->get();

        return view('scores.index', [
            'scores' => $scores,
            'assessments' => Assessment::query()->with(['lesson', 'section'])->orderBy('title')->get(),
            'filters' => [
                'assessment_id' => $assessmentId,
            ],
        ]);
    }

    public function create(Request $request): View
    {
        $selectedAssessmentId = $request->integer('assessment_id');
        $assessment = null;

        if ($selectedAssessmentId) {
            $assessment = Assessment::query()
                ->with('lesson')
                ->find($selectedAssessmentId);
        }

        $students = Student::query()
            ->when($assessment, function ($query) use ($assessment) {
                $query->whereIn('id', Enrollment::query()
                    ->where('section_id', $assessment->section_id)
                    ->when($assessment->lesson?->school_year_id, function ($enrollmentQuery, $schoolYearId) {
                        $enrollmentQuery->where('school_year_id', $schoolYearId);
                    })
                    ->select('student_id'));
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('scores.create', [
            'assessments' => Assessment::query()->with(['lesson', 'section'])->orderBy('title')->get(),
            'students' => $students,
            'selectedAssessmentId' => $selectedAssessmentId,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'assessment_id' => ['required', 'exists:assessments,id'],
            'student_id' => ['required', 'exists:students,id'],
            'score' => ['required', 'integer', 'min:0'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ]);

        $assessment = Assessment::query()
            ->with('lesson')
            ->findOrFail($validated['assessment_id']);

        if ($validated['score'] > $assessment->max_score) {
            return back()->withErrors([
                'score' => 'Score must not exceed the assessment max score.',
            ])->withInput();
        }

        $isEnrolled = Enrollment::query()
            ->where('student_id', $validated['student_id'])
            ->where('section_id', $assessment->section_id)
            ->when($assessment->lesson?->school_year_id, function ($enrollmentQuery, $schoolYearId) {
                $enrollmentQuery->where('school_year_id', $schoolYearId);
            })
            ->exists();

        if (! $isEnrolled) {
            return back()->withErrors([
                'student_id' => 'Student is not enrolled in the selected section.',
            ])->withInput();
        }

        $validated['recorded_by'] = Auth::id();

        Score::create($validated);

        return redirect()->route('scores.index');
    }

    public function edit(Score $score): View
    {
        return view('scores.edit', [
            'score' => $score,
            'assessments' => Assessment::query()->with(['lesson', 'section'])->orderBy('title')->get(),
            'students' => Student::query()->orderBy('last_name')->orderBy('first_name')->get(),
        ]);
    }

    public function update(Request $request, Score $score): RedirectResponse
    {
        $validated = $request->validate([
            'assessment_id' => ['required', 'exists:assessments,id'],
            'student_id' => ['required', 'exists:students,id'],
            'score' => ['required', 'integer', 'min:0'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ]);

        $assessment = Assessment::query()
            ->with('lesson')
            ->findOrFail($validated['assessment_id']);

        if ($validated['score'] > $assessment->max_score) {
            return back()->withErrors([
                'score' => 'Score must not exceed the assessment max score.',
            ])->withInput();
        }

        $isEnrolled = Enrollment::query()
            ->where('student_id', $validated['student_id'])
            ->where('section_id', $assessment->section_id)
            ->when($assessment->lesson?->school_year_id, function ($enrollmentQuery, $schoolYearId) {
                $enrollmentQuery->where('school_year_id', $schoolYearId);
            })
            ->exists();

        if (! $isEnrolled) {
            return back()->withErrors([
                'student_id' => 'Student is not enrolled in the selected section.',
            ])->withInput();
        }

        $score->update($validated);

        return redirect()->route('scores.index');
    }

    public function destroy(Score $score): RedirectResponse
    {
        $score->delete();

        return redirect()->route('scores.index');
    }
}
