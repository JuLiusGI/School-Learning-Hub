<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Enrollment;
use App\Models\Report;
use App\Models\SchoolYear;
use App\Models\Score;
use App\Models\Section;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $reportType = $request->string('report_type')->toString();

        $reports = Report::query()
            ->with(['section.grade', 'schoolYear'])
            ->when($reportType, function ($query) use ($reportType) {
                $query->where('report_type', $reportType);
            })
            ->orderByDesc('generated_at')
            ->get();

        return view('reports.index', [
            'reports' => $reports,
            'filters' => [
                'report_type' => $reportType,
            ],
            'reportTypes' => $this->reportTypes(),
        ]);
    }

    public function create(): View
    {
        return view('reports.create', [
            'sections' => Section::query()->with('grade')->orderBy('name')->get(),
            'schoolYears' => SchoolYear::query()->orderByDesc('is_active')->orderByDesc('start_date')->get(),
            'reportTypes' => $this->reportTypes(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'section_id' => ['required', 'exists:sections,id'],
            'school_year_id' => ['required', 'exists:school_years,id'],
            'report_type' => ['required', 'in:assessment-summary,student-progress'],
        ]);

        $section = Section::query()->with('grade')->findOrFail($validated['section_id']);
        $schoolYear = SchoolYear::query()->findOrFail($validated['school_year_id']);
        $generatedAt = Carbon::now();

        $assessments = Assessment::query()
            ->with(['lesson.subject', 'scores'])
            ->where('section_id', $section->id)
            ->whereHas('lesson', function ($query) use ($schoolYear) {
                $query->where('school_year_id', $schoolYear->id);
            })
            ->orderBy('date_given')
            ->get();

        $students = Enrollment::query()
            ->with('student')
            ->where('section_id', $section->id)
            ->where('school_year_id', $schoolYear->id)
            ->get()
            ->map->student
            ->filter()
            ->values();

        $payload = [
            'report_type' => $validated['report_type'],
            'section' => [
                'id' => $section->id,
                'name' => $section->name,
                'grade' => $section->grade?->level,
            ],
            'school_year' => [
                'id' => $schoolYear->id,
                'name' => $schoolYear->name,
            ],
            'generated_at' => $generatedAt->toDateTimeString(),
        ];

        if ($validated['report_type'] === 'assessment-summary') {
            $payload['summary'] = $assessments->map(function ($assessment) {
                $scoreValues = $assessment->scores->pluck('score');

                return [
                    'assessment_id' => $assessment->id,
                    'title' => $assessment->title,
                    'type' => $assessment->type,
                    'lesson_title' => $assessment->lesson?->title,
                    'subject' => $assessment->lesson?->subject?->name,
                    'max_score' => $assessment->max_score,
                    'date_given' => $assessment->date_given?->format('Y-m-d'),
                    'scores_count' => $scoreValues->count(),
                    'average_score' => $scoreValues->count() ? round($scoreValues->avg(), 2) : null,
                    'highest_score' => $scoreValues->max(),
                    'lowest_score' => $scoreValues->min(),
                ];
            })->values();
        }

        if ($validated['report_type'] === 'student-progress') {
            $assessmentIds = $assessments->pluck('id');
            $scoresByStudent = Score::query()
                ->whereIn('assessment_id', $assessmentIds)
                ->get()
                ->groupBy('student_id');

            $payload['summary'] = $students->map(function ($student) use ($scoresByStudent, $assessments) {
                $studentScores = $scoresByStudent->get($student->id, collect());
                $totalScore = $studentScores->sum('score');
                $assessmentsTaken = $studentScores->count();
                $totalMax = $studentScores->reduce(function ($carry, $score) use ($assessments) {
                    $assessment = $assessments->firstWhere('id', $score->assessment_id);

                    return $carry + ($assessment?->max_score ?? 0);
                }, 0);

                return [
                    'student_id' => $student->id,
                    'name' => $student->last_name.', '.$student->first_name,
                    'assessments_taken' => $assessmentsTaken,
                    'total_score' => $totalScore,
                    'total_max_score' => $totalMax,
                    'average_percent' => $totalMax > 0 ? round(($totalScore / $totalMax) * 100, 2) : null,
                ];
            })->values();
        }

        $report = Report::create([
            'section_id' => $section->id,
            'school_year_id' => $schoolYear->id,
            'report_type' => $validated['report_type'],
            'generated_at' => $generatedAt,
            'payload_json' => $payload,
        ]);

        return redirect()->route('reports.show', $report);
    }

    public function show(Report $report): View
    {
        return view('reports.show', [
            'report' => $report->load(['section.grade', 'schoolYear']),
            'payload' => $report->payload_json ?? [],
        ]);
    }

    public function destroy(Report $report): RedirectResponse
    {
        $report->delete();

        return redirect()->route('reports.index');
    }

    private function reportTypes(): array
    {
        return [
            'assessment-summary' => 'Assessment Summary',
            'student-progress' => 'Student Progress',
        ];
    }
}
