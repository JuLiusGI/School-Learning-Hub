<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Assessments
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-6">
                        <form method="GET" action="{{ route('assessments.index') }}" class="flex items-end gap-4">
                            <div>
                                <x-input-label for="lesson_id" value="Lesson" />
                                <select id="lesson_id" name="lesson_id" class="mt-1 block w-64 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">All lessons</option>
                                    @foreach ($lessons as $lesson)
                                        <option value="{{ $lesson->id }}" @selected($filters['lesson_id'] == $lesson->id)>{{ $lesson->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="section_id" value="Section" />
                                <select id="section_id" name="section_id" class="mt-1 block w-48 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">All sections</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}" @selected($filters['section_id'] == $section->id)>
                                            {{ $section->name }} ({{ $section->grade?->level }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <x-primary-button>
                                Filter
                            </x-primary-button>
                        </form>
                        <a href="{{ route('assessments.create') }}">
                            <x-primary-button>
                                Add Assessment
                            </x-primary-button>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="px-4 py-2 text-left">Title</th>
                                    <th class="px-4 py-2 text-left">Lesson</th>
                                    <th class="px-4 py-2 text-left">Section</th>
                                    <th class="px-4 py-2 text-left">Type</th>
                                    <th class="px-4 py-2 text-left">Max Score</th>
                                    <th class="px-4 py-2 text-left">Date Given</th>
                                    <th class="px-4 py-2 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assessments as $assessment)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $assessment->title }}</td>
                                        <td class="px-4 py-2">{{ $assessment->lesson?->title }}</td>
                                        <td class="px-4 py-2">{{ $assessment->section?->name }}</td>
                                        <td class="px-4 py-2">{{ $assessment->type }}</td>
                                        <td class="px-4 py-2">{{ $assessment->max_score }}</td>
                                        <td class="px-4 py-2">{{ $assessment->date_given?->format('Y-m-d') }}</td>
                                        <td class="px-4 py-2 text-right">
                                            <div class="inline-flex items-center gap-2">
                                                <a href="{{ route('assessment-items.index', ['assessment_id' => $assessment->id]) }}">
                                                    <x-secondary-button>
                                                        Items
                                                    </x-secondary-button>
                                                </a>
                                                <a href="{{ route('scores.index', ['assessment_id' => $assessment->id]) }}">
                                                    <x-secondary-button>
                                                        Scores
                                                    </x-secondary-button>
                                                </a>
                                                <a href="{{ route('assessments.edit', $assessment) }}">
                                                    <x-secondary-button>
                                                        Edit
                                                    </x-secondary-button>
                                                </a>
                                                <form method="POST" action="{{ route('assessments.destroy', $assessment) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-danger-button>
                                                        Delete
                                                    </x-danger-button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
