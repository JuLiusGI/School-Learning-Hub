<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Assessment
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('assessments.update', $assessment) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="title" value="Title" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" value="{{ old('title', $assessment->title) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="lesson_id" value="Lesson" />
                                <select id="lesson_id" name="lesson_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select lesson</option>
                                    @foreach ($lessons as $lesson)
                                        <option value="{{ $lesson->id }}" @selected(old('lesson_id', $assessment->lesson_id) == $lesson->id)>
                                            {{ $lesson->title }} - {{ $lesson->subject?->name }} ({{ $lesson->grade?->level }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('lesson_id')" />
                            </div>
                            <div>
                                <x-input-label for="section_id" value="Section" />
                                <select id="section_id" name="section_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select section</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}" @selected(old('section_id', $assessment->section_id) == $section->id)>
                                            {{ $section->name }} ({{ $section->grade?->level }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('section_id')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="type" value="Type" />
                                <x-text-input id="type" name="type" type="text" class="mt-1 block w-full" value="{{ old('type', $assessment->type) }}" required />
                                <x-input-error class="mt-2" :messages="$errors->get('type')" />
                            </div>
                            <div>
                                <x-input-label for="max_score" value="Max Score" />
                                <x-text-input id="max_score" name="max_score" type="number" min="1" class="mt-1 block w-full" value="{{ old('max_score', $assessment->max_score) }}" required />
                                <x-input-error class="mt-2" :messages="$errors->get('max_score')" />
                            </div>
                            <div>
                                <x-input-label for="date_given" value="Date Given" />
                                <x-text-input id="date_given" name="date_given" type="date" class="mt-1 block w-full" value="{{ old('date_given', $assessment->date_given?->format('Y-m-d')) }}" required />
                                <x-input-error class="mt-2" :messages="$errors->get('date_given')" />
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <x-primary-button>
                                Update Assessment
                            </x-primary-button>
                            <a href="{{ route('assessments.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Assessment Items</h3>
                        <a href="{{ route('assessment-items.create', ['assessment_id' => $assessment->id]) }}">
                            <x-primary-button>
                                Add Item
                            </x-primary-button>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="px-4 py-2 text-left">Item No</th>
                                    <th class="px-4 py-2 text-left">Question</th>
                                    <th class="px-4 py-2 text-left">Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($assessment->items as $item)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $item->item_no }}</td>
                                        <td class="px-4 py-2">{{ $item->question_text }}</td>
                                        <td class="px-4 py-2">{{ $item->points }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="px-4 py-4 text-gray-500" colspan="3">No items yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Scores</h3>
                        <a href="{{ route('scores.create', ['assessment_id' => $assessment->id]) }}">
                            <x-primary-button>
                                Add Score
                            </x-primary-button>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="px-4 py-2 text-left">Student</th>
                                    <th class="px-4 py-2 text-left">Score</th>
                                    <th class="px-4 py-2 text-left">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($assessment->scores as $score)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">
                                            {{ $score->student?->last_name }}, {{ $score->student?->first_name }}
                                        </td>
                                        <td class="px-4 py-2">{{ $score->score }}</td>
                                        <td class="px-4 py-2">{{ $score->remarks }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="px-4 py-4 text-gray-500" colspan="3">No scores yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
