<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Score
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('scores.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="assessment_id" value="Assessment" />
                            <select id="assessment_id" name="assessment_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select assessment</option>
                                @foreach ($assessments as $assessment)
                                    <option value="{{ $assessment->id }}" @selected(old('assessment_id', $selectedAssessmentId) == $assessment->id)>
                                        {{ $assessment->title }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('assessment_id')" />
                        </div>

                        <div>
                            <x-input-label for="student_id" value="Student" />
                            <select id="student_id" name="student_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select student</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>
                                        {{ $student->last_name }}, {{ $student->first_name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('student_id')" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="score" value="Score" />
                                <x-text-input id="score" name="score" type="number" min="0" class="mt-1 block w-full" value="{{ old('score') }}" required />
                                <x-input-error class="mt-2" :messages="$errors->get('score')" />
                            </div>
                            <div>
                                <x-input-label for="remarks" value="Remarks (Optional)" />
                                <x-text-input id="remarks" name="remarks" type="text" class="mt-1 block w-full" value="{{ old('remarks') }}" />
                                <x-input-error class="mt-2" :messages="$errors->get('remarks')" />
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <x-primary-button>
                                Save Score
                            </x-primary-button>
                            <a href="{{ route('scores.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
