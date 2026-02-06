<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Lesson;
use App\Models\Section;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssessmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Assessment::query()
            ->with(['lesson.subject', 'section.grade'])
            ->orderByDesc('date_given');

        $lessonId = $request->integer('lesson_id');
        $sectionId = $request->integer('section_id');
        $sectionIds = $this->teacherSectionIds();

        if ($sectionIds !== null) {
            $query->whereIn('section_id', $sectionIds);
        }

        if ($lessonId) {
            $query->where('lesson_id', $lessonId);
        }

        if ($sectionId) {
            $this->ensureTeacherSectionAccess($sectionIds, $sectionId);
            $query->where('section_id', $sectionId);
        }

        $assessments = $query->get();

        return view('assessments.index', [
            'assessments' => $assessments,
            'lessons' => Lesson::query()->with(['subject', 'grade'])->orderBy('title')->get(),
            'sections' => Section::query()
                ->with('grade')
                ->when($sectionIds !== null, fn ($query) => $query->whereIn('id', $sectionIds))
                ->orderBy('name')
                ->get(),
            'filters' => [
                'lesson_id' => $lessonId,
                'section_id' => $sectionId,
            ],
        ]);
    }

    public function create(): View
    {
        $sectionIds = $this->teacherSectionIds();

        return view('assessments.create', [
            'lessons' => Lesson::query()->with(['subject', 'grade'])->orderBy('title')->get(),
            'sections' => Section::query()
                ->with('grade')
                ->when($sectionIds !== null, fn ($query) => $query->whereIn('id', $sectionIds))
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'lesson_id' => ['required', 'exists:lessons,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'title' => ['required', 'string', 'max:200'],
            'type' => ['required', 'string', 'max:50'],
            'max_score' => ['required', 'integer', 'min:1'],
            'date_given' => ['required', 'date'],
        ]);

        $this->ensureTeacherSectionAccess($this->teacherSectionIds(), (int) $validated['section_id']);

        Assessment::create($validated);

        return redirect()->route('assessments.index');
    }

    public function edit(Assessment $assessment): View
    {
        $sectionIds = $this->teacherSectionIds();
        if ($sectionIds !== null && ! in_array($assessment->section_id, $sectionIds, true)) {
            abort(403);
        }

        return view('assessments.edit', [
            'assessment' => $assessment->load(['lesson', 'section', 'items', 'scores.student']),
            'lessons' => Lesson::query()->with(['subject', 'grade'])->orderBy('title')->get(),
            'sections' => Section::query()
                ->with('grade')
                ->when($sectionIds !== null, fn ($query) => $query->whereIn('id', $sectionIds))
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(Request $request, Assessment $assessment): RedirectResponse
    {
        $validated = $request->validate([
            'lesson_id' => ['required', 'exists:lessons,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'title' => ['required', 'string', 'max:200'],
            'type' => ['required', 'string', 'max:50'],
            'max_score' => ['required', 'integer', 'min:1'],
            'date_given' => ['required', 'date'],
        ]);

        $this->ensureTeacherSectionAccess($this->teacherSectionIds(), (int) $validated['section_id']);

        if ($conflict = $this->ensureNoConflict($request, $assessment)) {
            return $conflict;
        }

        $assessment->update($validated);

        return redirect()->route('assessments.index');
    }

    public function destroy(Assessment $assessment): RedirectResponse
    {
        $sectionIds = $this->teacherSectionIds();
        if ($sectionIds !== null && ! in_array($assessment->section_id, $sectionIds, true)) {
            abort(403);
        }

        $assessment->delete();

        return redirect()->route('assessments.index');
    }
}
