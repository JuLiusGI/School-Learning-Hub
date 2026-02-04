<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SyncController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'actions' => ['required', 'array'],
            'actions.*.id' => ['required'],
            'actions.*.url' => ['required', 'string'],
            'actions.*.method' => ['required', 'string'],
            'actions.*.data' => ['nullable', 'array'],
        ]);

        $processedIds = [];

        foreach ($validated['actions'] as $action) {
            $data = Arr::get($action, 'data', []);
            $method = strtoupper($action['method']);
            $subRequest = Request::create($action['url'], $method, $data);
            $subRequest->setLaravelSession($request->session());
            $subRequest->setUserResolver(fn () => $request->user());
            $subRequest->headers->set('X-Requested-With', 'XMLHttpRequest');
            $subRequest->headers->set('X-Offline-Sync', '1');

            $response = app()->handle($subRequest);

            if ($response->getStatusCode() === 409) {
                return response()->json([
                    'message' => 'Conflict detected.',
                ], 409);
            }

            if ($response->getStatusCode() >= 400) {
                return response()->json([
                    'message' => 'Sync failed.',
                ], 422);
            }

            $processedIds[] = $action['id'];
        }

        return response()->json([
            'processed_ids' => $processedIds,
        ]);
    }
}
