<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\LessonResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LessonResourceController extends Controller
{
    public function index(Request $request): View
    {
        $query = LessonResource::query()
            ->with(['lesson.subject', 'lesson.grade', 'lesson.schoolYear'])
            ->orderByDesc('created_at');

        $lessonId = $request->integer('lesson_id');
        $gradeIds = $this->teacherGradeIds();

        if ($gradeIds !== null) {
            $query->whereHas('lesson', fn ($lessonQuery) => $lessonQuery->whereIn('grade_id', $gradeIds));
        }

        if ($lessonId) {
            if ($gradeIds !== null) {
                $lessonGradeId = Lesson::query()->whereKey($lessonId)->value('grade_id');
                if ($lessonGradeId && ! in_array($lessonGradeId, $gradeIds, true)) {
                    abort(403);
                }
            }
            $query->where('lesson_id', $lessonId);
        }

        $resources = $query->get();

        return view('lesson_resources.index', [
            'resources' => $resources,
            'lessons' => Lesson::query()
                ->when($gradeIds !== null, fn ($lessonQuery) => $lessonQuery->whereIn('grade_id', $gradeIds))
                ->orderBy('title')
                ->get(),
            'filters' => [
                'lesson_id' => $lessonId,
            ],
        ]);
    }

    public function create(): View
    {
        $gradeIds = $this->teacherGradeIds();

        return view('lesson_resources.create', [
            'lessons' => Lesson::query()
                ->with(['subject', 'grade', 'schoolYear'])
                ->when($gradeIds !== null, fn ($lessonQuery) => $lessonQuery->whereIn('grade_id', $gradeIds))
                ->orderBy('title')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'lesson_id' => ['required', 'exists:lessons,id'],
            'title' => ['nullable', 'string', 'max:200'],
            'type' => ['required', 'string', 'max:50'],
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $lessonGradeId = Lesson::query()->whereKey($validated['lesson_id'])->value('grade_id');
        if ($lessonGradeId) {
            $this->ensureTeacherGradeAccess($this->teacherGradeIds(), (int) $lessonGradeId);
        }

        $path = $request->file('file')->store('lesson-resources');

        LessonResource::create([
            'lesson_id' => $validated['lesson_id'],
            'title' => $validated['title'],
            'type' => $validated['type'],
            'file_path' => $path,
        ]);

        return redirect()->route('lesson-resources.index');
    }

    public function edit(LessonResource $lessonResource): View
    {
        $gradeIds = $this->teacherGradeIds();
        if ($gradeIds !== null && ! in_array($lessonResource->lesson?->grade_id, $gradeIds, true)) {
            abort(403);
        }

        return view('lesson_resources.edit', [
            'lessonResource' => $lessonResource,
            'lessons' => Lesson::query()
                ->with(['subject', 'grade', 'schoolYear'])
                ->when($gradeIds !== null, fn ($lessonQuery) => $lessonQuery->whereIn('grade_id', $gradeIds))
                ->orderBy('title')
                ->get(),
        ]);
    }

    public function update(Request $request, LessonResource $lessonResource): RedirectResponse
    {
        $validated = $request->validate([
            'lesson_id' => ['required', 'exists:lessons,id'],
            'title' => ['nullable', 'string', 'max:200'],
            'type' => ['required', 'string', 'max:50'],
            'file' => ['nullable', 'file', 'max:10240'],
        ]);

        $lessonGradeId = Lesson::query()->whereKey($validated['lesson_id'])->value('grade_id');
        if ($lessonGradeId) {
            $this->ensureTeacherGradeAccess($this->teacherGradeIds(), (int) $lessonGradeId);
        }

        if ($conflict = $this->ensureNoConflict($request, $lessonResource)) {
            return $conflict;
        }

        $data = [
            'lesson_id' => $validated['lesson_id'],
            'title' => $validated['title'],
            'type' => $validated['type'],
        ];

        if ($request->hasFile('file')) {
            Storage::delete($lessonResource->file_path);
            $data['file_path'] = $request->file('file')->store('lesson-resources');
        }

        $lessonResource->update($data);

        return redirect()->route('lesson-resources.index');
    }

    public function destroy(LessonResource $lessonResource): RedirectResponse
    {
        $gradeIds = $this->teacherGradeIds();
        if ($gradeIds !== null && ! in_array($lessonResource->lesson?->grade_id, $gradeIds, true)) {
            abort(403);
        }

        Storage::delete($lessonResource->file_path);
        $lessonResource->delete();

        return redirect()->route('lesson-resources.index');
    }
}
