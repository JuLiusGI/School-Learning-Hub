<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Assessment
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('assessments.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="title" value="Title" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" value="{{ old('title') }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="lesson_id" value="Lesson" />
                                <select id="lesson_id" name="lesson_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select lesson</option>
                                    @foreach ($lessons as $lesson)
                                        <option value="{{ $lesson->id }}" @selected(old('lesson_id') == $lesson->id)>
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
                                        <option value="{{ $section->id }}" @selected(old('section_id') == $section->id)>
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
                                <x-text-input id="type" name="type" type="text" class="mt-1 block w-full" value="{{ old('type') }}" required />
                                <x-input-error class="mt-2" :messages="$errors->get('type')" />
                            </div>
                            <div>
                                <x-input-label for="max_score" value="Max Score" />
                                <x-text-input id="max_score" name="max_score" type="number" min="1" class="mt-1 block w-full" value="{{ old('max_score') }}" required />
                                <x-input-error class="mt-2" :messages="$errors->get('max_score')" />
                            </div>
                            <div>
                                <x-input-label for="date_given" value="Date Given" />
                                <x-text-input id="date_given" name="date_given" type="date" class="mt-1 block w-full" value="{{ old('date_given') }}" required />
                                <x-input-error class="mt-2" :messages="$errors->get('date_given')" />
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <x-primary-button>
                                Save Assessment
                            </x-primary-button>
                            <a href="{{ route('assessments.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
