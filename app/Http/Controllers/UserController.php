<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->orderBy('name')
            ->get();

        return view('users.index', [
            'users' => $users,
        ]);
    }

    public function create(): View
    {
        return view('users.create', [
            'sections' => Section::query()
                ->with('grade')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,head_teacher,teacher'],
            'status' => ['required', 'in:active,inactive'],
            'section_ids' => ['array'],
            'section_ids.*' => ['integer', 'exists:sections,id'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);

        $sectionIds = $validated['section_ids'] ?? [];
        $user->assignedSections()->sync($validated['role'] === 'teacher' ? $sectionIds : []);

        return redirect()
            ->route('users.index');
    }
}
