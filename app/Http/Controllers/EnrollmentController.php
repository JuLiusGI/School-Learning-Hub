<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function index(): View
    {
        $query = Enrollment::query()
            ->with(['student', 'section.grade', 'schoolYear'])
            ->orderByDesc('school_year_id');

        $sectionIds = $this->teacherSectionIds();
        if ($sectionIds !== null) {
            $query->whereIn('section_id', $sectionIds);
        }

        $enrollments = $query->get();

        return view('enrollments.index', [
            'enrollments' => $enrollments,
        ]);
    }

    public function create(): View
    {
        $sectionIds = $this->teacherSectionIds();

        return view('enrollments.create', [
            'students' => Student::query()
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->get(),
            'sections' => Section::query()
                ->with('grade')
                ->when($sectionIds !== null, fn ($query) => $query->whereIn('id', $sectionIds))
                ->orderBy('name')
                ->get(),
            'schoolYears' => SchoolYear::query()
                ->orderByDesc('is_active')
                ->orderByDesc('start_date')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'school_year_id' => ['required', 'exists:school_years,id'],
        ]);

        $this->ensureTeacherSectionAccess($this->teacherSectionIds(), (int) $validated['section_id']);

        $request->validate([
            'student_id' => [
                Rule::unique('enrollments', 'student_id')->where(fn ($query) => $query->where('school_year_id', $validated['school_year_id'])),
            ],
        ]);

        Enrollment::create($validated);

        return redirect()->route('enrollments.index');
    }

    public function edit(Enrollment $enrollment): View
    {
        $sectionIds = $this->teacherSectionIds();

        if ($sectionIds !== null && ! in_array($enrollment->section_id, $sectionIds, true)) {
            abort(403);
        }

        return view('enrollments.edit', [
            'enrollment' => $enrollment,
            'students' => Student::query()
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->get(),
            'sections' => Section::query()
                ->with('grade')
                ->when($sectionIds !== null, fn ($query) => $query->whereIn('id', $sectionIds))
                ->orderBy('name')
                ->get(),
            'schoolYears' => SchoolYear::query()
                ->orderByDesc('is_active')
                ->orderByDesc('start_date')
                ->get(),
        ]);
    }

    public function update(Request $request, Enrollment $enrollment): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'school_year_id' => ['required', 'exists:school_years,id'],
        ]);

        $this->ensureTeacherSectionAccess($this->teacherSectionIds(), (int) $validated['section_id']);

        $request->validate([
            'student_id' => [
                Rule::unique('enrollments', 'student_id')
                    ->where(fn ($query) => $query->where('school_year_id', $validated['school_year_id']))
                    ->ignore($enrollment->id),
            ],
        ]);

        if ($conflict = $this->ensureNoConflict($request, $enrollment)) {
            return $conflict;
        }

        $enrollment->update($validated);

        return redirect()->route('enrollments.index');
    }

    public function destroy(Enrollment $enrollment): RedirectResponse
    {
        $sectionIds = $this->teacherSectionIds();
        if ($sectionIds !== null && ! in_array($enrollment->section_id, $sectionIds, true)) {
            abort(403);
        }

        $enrollment->delete();

        return redirect()->route('enrollments.index');
    }
}
