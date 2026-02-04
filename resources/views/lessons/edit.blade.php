<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Lesson
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('lessons.update', $lesson) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="updated_at" value="{{ $lesson->updated_at?->toDateTimeString() }}">

                        <div>
                            <x-input-label for="title" value="Title" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" value="{{ old('title', $lesson->title) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="subject_id" value="Subject" />
                                <select id="subject_id" name="subject_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select subject</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}" @selected(old('subject_id', $lesson->subject_id) == $subject->id)>{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('subject_id')" />
                            </div>
                            <div>
                                <x-input-label for="grade_id" value="Grade" />
                                <select id="grade_id" name="grade_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select grade</option>
                                    @foreach ($grades as $grade)
                                        <option value="{{ $grade->id }}" @selected(old('grade_id', $lesson->grade_id) == $grade->id)>{{ $grade->level }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('grade_id')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="competency_id" value="Competency (Optional)" />
                                <select id="competency_id" name="competency_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">No competency</option>
                                    @foreach ($competencies as $competency)
                                        <option value="{{ $competency->id }}" @selected(old('competency_id', $lesson->competency_id) == $competency->id)>
                                            {{ $competency->code }} - {{ $competency->subject?->name }} ({{ $competency->grade?->level }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('competency_id')" />
                            </div>
                            <div>
                                <x-input-label for="school_year_id" value="School Year" />
                                <select id="school_year_id" name="school_year_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select school year</option>
                                    @foreach ($schoolYears as $schoolYear)
                                        <option value="{{ $schoolYear->id }}" @selected(old('school_year_id', $lesson->school_year_id) == $schoolYear->id)>
                                            {{ $schoolYear->name }}{{ $schoolYear->is_active ? ' (Active)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('school_year_id')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="objectives" value="Objectives" />
                            <textarea id="objectives" name="objectives" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('objectives', $lesson->objectives) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('objectives')" />
                        </div>

                        <div>
                            <x-input-label for="materials" value="Materials" />
                            <textarea id="materials" name="materials" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('materials', $lesson->materials) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('materials')" />
                        </div>

                        <div>
                            <x-input-label for="procedure" value="Procedure" />
                            <textarea id="procedure" name="procedure" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="6">{{ old('procedure', $lesson->procedure) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('procedure')" />
                        </div>

                        <div>
                            <x-input-label for="assessment_method" value="Assessment Method" />
                            <textarea id="assessment_method" name="assessment_method" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('assessment_method', $lesson->assessment_method) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('assessment_method')" />
                        </div>

                        <div class="flex items-center gap-3">
                            <x-primary-button>
                                Update Lesson
                            </x-primary-button>
                            <a href="{{ route('lessons.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Resources</h3>
                        <a href="{{ route('lesson-resources.create', ['lesson_id' => $lesson->id]) }}">
                            <x-primary-button>
                                Add Resource
                            </x-primary-button>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="px-4 py-2 text-left">Title</th>
                                    <th class="px-4 py-2 text-left">Type</th>
                                    <th class="px-4 py-2 text-left">File</th>
                                    <th class="px-4 py-2 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($lesson->resources as $resource)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $resource->title ?? 'Untitled' }}</td>
                                        <td class="px-4 py-2">{{ $resource->type }}</td>
                                        <td class="px-4 py-2">
                                            <a href="{{ Storage::url($resource->file_path) }}" class="text-indigo-600 hover:text-indigo-900" target="_blank" rel="noopener">
                                                View
                                            </a>
                                        </td>
                                        <td class="px-4 py-2 text-right">
                                            <div class="inline-flex items-center gap-2">
                                                <a href="{{ route('lesson-resources.edit', $resource) }}">
                                                    <x-secondary-button>
                                                        Edit
                                                    </x-secondary-button>
                                                </a>
                                                <form method="POST" action="{{ route('lesson-resources.destroy', $resource) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-danger-button>
                                                        Delete
                                                    </x-danger-button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="px-4 py-4 text-gray-500" colspan="4">No resources yet.</td>
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
