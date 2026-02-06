<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssessmentItemController extends Controller
{
    public function index(Request $request): View
    {
        $query = AssessmentItem::query()
            ->with(['assessment.lesson.subject', 'assessment.section.grade'])
            ->orderBy('item_no');

        $assessmentId = $request->integer('assessment_id');
        $sectionIds = $this->teacherSectionIds();

        if ($sectionIds !== null) {
            $query->whereHas('assessment', fn ($assessmentQuery) => $assessmentQuery->whereIn('section_id', $sectionIds));
        }

        if ($assessmentId) {
            if ($sectionIds !== null) {
                $assessmentSectionId = Assessment::query()->whereKey($assessmentId)->value('section_id');
                if ($assessmentSectionId && ! in_array($assessmentSectionId, $sectionIds, true)) {
                    abort(403);
                }
            }
            $query->where('assessment_id', $assessmentId);
        }

        $items = $query->get();

        return view('assessment_items.index', [
            'items' => $items,
            'assessments' => Assessment::query()
                ->with(['lesson', 'section'])
                ->when($sectionIds !== null, fn ($assessmentQuery) => $assessmentQuery->whereIn('section_id', $sectionIds))
                ->orderBy('title')
                ->get(),
            'filters' => [
                'assessment_id' => $assessmentId,
            ],
        ]);
    }

    public function create(Request $request): View
    {
        $sectionIds = $this->teacherSectionIds();

        return view('assessment_items.create', [
            'assessments' => Assessment::query()
                ->with(['lesson', 'section'])
                ->when($sectionIds !== null, fn ($assessmentQuery) => $assessmentQuery->whereIn('section_id', $sectionIds))
                ->orderBy('title')
                ->get(),
            'selectedAssessmentId' => $request->integer('assessment_id'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'assessment_id' => ['required', 'exists:assessments,id'],
            'item_no' => ['required', 'integer', 'min:1'],
            'question_text' => ['required', 'string'],
            'correct_answer' => ['nullable', 'string'],
            'points' => ['required', 'integer', 'min:0'],
        ]);

        $assessmentSectionId = Assessment::query()->whereKey($validated['assessment_id'])->value('section_id');
        if ($assessmentSectionId) {
            $this->ensureTeacherSectionAccess($this->teacherSectionIds(), (int) $assessmentSectionId);
        }

        AssessmentItem::create($validated);

        return redirect()->route('assessment-items.index');
    }

    public function edit(AssessmentItem $assessmentItem): View
    {
        $sectionIds = $this->teacherSectionIds();
        if ($sectionIds !== null && ! in_array($assessmentItem->assessment?->section_id, $sectionIds, true)) {
            abort(403);
        }

        return view('assessment_items.edit', [
            'assessmentItem' => $assessmentItem,
            'assessments' => Assessment::query()
                ->with(['lesson', 'section'])
                ->when($sectionIds !== null, fn ($assessmentQuery) => $assessmentQuery->whereIn('section_id', $sectionIds))
                ->orderBy('title')
                ->get(),
        ]);
    }

    public function update(Request $request, AssessmentItem $assessmentItem): RedirectResponse
    {
        $validated = $request->validate([
            'assessment_id' => ['required', 'exists:assessments,id'],
            'item_no' => ['required', 'integer', 'min:1'],
            'question_text' => ['required', 'string'],
            'correct_answer' => ['nullable', 'string'],
            'points' => ['required', 'integer', 'min:0'],
        ]);

        $assessmentSectionId = Assessment::query()->whereKey($validated['assessment_id'])->value('section_id');
        if ($assessmentSectionId) {
            $this->ensureTeacherSectionAccess($this->teacherSectionIds(), (int) $assessmentSectionId);
        }

        if ($conflict = $this->ensureNoConflict($request, $assessmentItem)) {
            return $conflict;
        }

        $assessmentItem->update($validated);

        return redirect()->route('assessment-items.index');
    }

    public function destroy(AssessmentItem $assessmentItem): RedirectResponse
    {
        $sectionIds = $this->teacherSectionIds();
        if ($sectionIds !== null && ! in_array($assessmentItem->assessment?->section_id, $sectionIds, true)) {
            abort(403);
        }

        $assessmentItem->delete();

        return redirect()->route('assessment-items.index');
    }
}
