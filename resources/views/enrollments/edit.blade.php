<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Enrollment
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('enrollments.update', $enrollment) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="school_year_id" value="School Year" />
                            <select id="school_year_id" name="school_year_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select school year</option>
                                @foreach ($schoolYears as $schoolYear)
                                    <option value="{{ $schoolYear->id }}" @selected(old('school_year_id', $enrollment->school_year_id) == $schoolYear->id)>
                                        {{ $schoolYear->name }}{{ $schoolYear->is_active ? ' (Active)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('school_year_id')" />
                        </div>

                        <div>
                            <x-input-label for="student_id" value="Student" />
                            <select id="student_id" name="student_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select student</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}" @selected(old('student_id', $enrollment->student_id) == $student->id)>
                                        {{ $student->last_name }}, {{ $student->first_name }} ({{ $student->lrn }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('student_id')" />
                        </div>

                        <div>
                            <x-input-label for="section_id" value="Section" />
                            <select id="section_id" name="section_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select section</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}" @selected(old('section_id', $enrollment->section_id) == $section->id)>
                                        {{ $section->name }} ({{ $section->grade?->level }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('section_id')" />
                        </div>

                        <div class="flex items-center gap-3">
                            <x-primary-button>
                                Update Enrollment
                            </x-primary-button>
                            <a href="{{ route('enrollments.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
