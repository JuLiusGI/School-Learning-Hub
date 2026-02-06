<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

abstract class Controller
{
    protected function teacherSectionIds(): ?array
    {
        $user = request()->user();

        if (! $user || ! $user->isTeacher()) {
            return null;
        }

        return $user->assignedSections()
            ->pluck('sections.id')
            ->all();
    }

    protected function teacherGradeIds(): ?array
    {
        $user = request()->user();

        if (! $user || ! $user->isTeacher()) {
            return null;
        }

        return $user->assignedSections()
            ->pluck('grade_id')
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    protected function ensureTeacherSectionAccess(?array $sectionIds, int $sectionId): void
    {
        if ($sectionIds !== null && ! in_array($sectionId, $sectionIds, true)) {
            abort(403);
        }
    }

    protected function ensureTeacherGradeAccess(?array $gradeIds, int $gradeId): void
    {
        if ($gradeIds !== null && ! in_array($gradeId, $gradeIds, true)) {
            abort(403);
        }
    }

    protected function ensureNoConflict(Request $request, Model $model): JsonResponse|RedirectResponse|null
    {
        $clientUpdatedAt = $request->input('updated_at');

        if (! $clientUpdatedAt) {
            return null;
        }

        $serverUpdatedAt = $model->updated_at?->toDateTimeString();

        if ($serverUpdatedAt && $serverUpdatedAt !== $clientUpdatedAt) {
            if ($request->expectsJson() || $request->header('X-Offline-Sync')) {
                return response()->json([
                    'message' => 'Conflict detected.',
                    'server_updated_at' => $serverUpdatedAt,
                ], 409);
            }

            return back()
                ->withErrors(['updated_at' => 'This record was updated elsewhere. Refresh and try again.'])
                ->withInput();
        }

        return null;
    }
}
