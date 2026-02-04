<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

abstract class Controller
{
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
