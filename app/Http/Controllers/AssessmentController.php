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

        if ($lessonId) {
            $query->where('lesson_id', $lessonId);
        }

        if ($sectionId) {
            $query->where('section_id', $sectionId);
        }

        $assessments = $query->get();

        return view('assessments.index', [
            'assessments' => $assessments,
            'lessons' => Lesson::query()->with(['subject', 'grade'])->orderBy('title')->get(),
            'sections' => Section::query()->with('grade')->orderBy('name')->get(),
            'filters' => [
                'lesson_id' => $lessonId,
                'section_id' => $sectionId,
            ],
        ]);
    }

    public function create(): View
    {
        return view('assessments.create', [
            'lessons' => Lesson::query()->with(['subject', 'grade'])->orderBy('title')->get(),
            'sections' => Section::query()->with('grade')->orderBy('name')->get(),
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

        Assessment::create($validated);

        return redirect()->route('assessments.index');
    }

    public function edit(Assessment $assessment): View
    {
        return view('assessments.edit', [
            'assessment' => $assessment->load(['lesson', 'section', 'items', 'scores.student']),
            'lessons' => Lesson::query()->with(['subject', 'grade'])->orderBy('title')->get(),
            'sections' => Section::query()->with('grade')->orderBy('name')->get(),
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

        $assessment->update($validated);

        return redirect()->route('assessments.index');
    }

    public function destroy(Assessment $assessment): RedirectResponse
    {
        $assessment->delete();

        return redirect()->route('assessments.index');
    }
}
