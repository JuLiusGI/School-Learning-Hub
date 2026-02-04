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

        if ($assessmentId) {
            $query->where('assessment_id', $assessmentId);
        }

        $items = $query->get();

        return view('assessment_items.index', [
            'items' => $items,
            'assessments' => Assessment::query()->with(['lesson', 'section'])->orderBy('title')->get(),
            'filters' => [
                'assessment_id' => $assessmentId,
            ],
        ]);
    }

    public function create(Request $request): View
    {
        return view('assessment_items.create', [
            'assessments' => Assessment::query()->with(['lesson', 'section'])->orderBy('title')->get(),
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

        AssessmentItem::create($validated);

        return redirect()->route('assessment-items.index');
    }

    public function edit(AssessmentItem $assessmentItem): View
    {
        return view('assessment_items.edit', [
            'assessmentItem' => $assessmentItem,
            'assessments' => Assessment::query()->with(['lesson', 'section'])->orderBy('title')->get(),
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

        $assessmentItem->update($validated);

        return redirect()->route('assessment-items.index');
    }

    public function destroy(AssessmentItem $assessmentItem): RedirectResponse
    {
        $assessmentItem->delete();

        return redirect()->route('assessment-items.index');
    }
}
